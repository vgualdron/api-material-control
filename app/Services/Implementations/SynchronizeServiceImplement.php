<?php
    namespace App\Services\Implementations;
    use Illuminate\Contracts\Auth\Factory as Auth;
    use Illuminate\Support\Facades\DB;
    use App\Services\Interfaces\SynchronizeServiceInterface;
    use GuzzleHttp\Client;
    use App\Models\Material;
    use App\Models\ModelHasRoles;
    use App\Models\AuthTokens;
    use App\Models\Permission;
    use App\Models\Role;
    use App\Models\RolePermission;
    use App\Models\Zone;
    use App\Models\Yard;
    use App\Models\User;
    use App\Models\ThirdFB;
    use App\Models\Tiquet;
    
    class SynchronizeServiceImplement implements SynchronizeServiceInterface{
        
        private $client;
        private $material;
        private $modelHasRoles;
        private $authTokens;
        private $permission;
        private $role;
        private $rolePermission;
        private $zone;
        private $yard;
        private $user;
        private $userRol;
        private $thirdFB;
        private $tiquet;
        protected $auth;

        function __construct(Auth $auth){
            $this->material = new Material;
            $this->modelHasRoles = new ModelHasRoles;
            //$this->authTokens = new AuthTokens;
            $this->permission = new Permission;
            $this->role = new Role;
            $this->rolePermission = new RolePermission;
            $this->zone = new Zone;
            $this->yard = new Yard;
            $this->user = new User;
            $this->userRol = new ModelHasRoles;
            $this->thirdFB = new ThirdFB;
            $this->tiquet = new Tiquet;
            $this->auth = $auth;
            $this->client = new Client(['base_uri' => 'http://201.244.209.139']);
        } 
        
        function toServer(array $objects){
            try {
                DB::transaction(function () use($objects) {
                    foreach($objects as $tiquetObject){
                        if($tiquetObject['deleted'] == 1){
                            $tiquet = $this->tiquet->find($tiquetObject['id']);
                            if(!empty($tiquet)){
                                $tiquet->delete();
                            }
                        } 
                        else if($tiquetObject['synchronized'] == 1) {
                            $tiquet = $this->tiquet->find($tiquetObject['id']);
                            if(!empty($tiquet)){
                                $type = $tiquetObject['type'];
                                $referral_number = $tiquetObject['referral_number'];
                                $receipt_number = $tiquetObject['receipt_number'];
                                $operation = $tiquetObject['operation'];
                                $tiquet = null;
                                $tiquet = $this->tiquet->whereRaw('CASE 
                                                                    WHEN "'.$type.'" = "D" THEN referral_number = "'.$referral_number.'" AND type <> "R"
                                                    WHEN "'.$type.'" = "V" OR (("'.$type.'" = "OC" OR "'.$type.'" = "OP") AND "'.$operation.'" = "P") THEN referral_number = "'.$referral_number.'"
                                                    WHEN "'.$type.'" = "C" OR "'.$type.'" = "R" OR (("'.$type.'" = "OC" OR "'.$type.'" = "OP") AND "'.$operation.'" = "D") THEN receipt_number = "'.$receipt_number.'"
                                                END AND id <> '.$tiquetObject['id'])
                                            ->selectRaw("*")
                                            ->get();
                                if(count($tiquet) == 0) {
                                    $this->tiquet->where('id', $tiquetObject['id'])->first()
                                        ->fill($tiquetObject)->save();
                                }
                            }
                        }
                        else {
                            unset($tiquetObject['id']);
                            $type = $tiquetObject['type'];
                            $referral_number = $tiquetObject['referral_number'];
                            $receipt_number = $tiquetObject['receipt_number'];
                            $operation = $tiquetObject['operation'];
                            $tiquet = null;
                            $tiquet = $this->tiquet->whereRaw('CASE 
                                                                WHEN "'.$type.'" = "D" THEN referral_number = "'.$referral_number.'" AND type <> "R"
                                                WHEN "'.$type.'" = "V" OR (("'.$type.'" = "OC" OR "'.$type.'" = "OP") AND "'.$operation.'" = "P") THEN referral_number = "'.$referral_number.'"
                                                WHEN "'.$type.'" = "C" OR "'.$type.'" = "R" OR (("'.$type.'" = "OC" OR "'.$type.'" = "OP") AND "'.$operation.'" = "D") THEN receipt_number = "'.$receipt_number.'"
                                            END')
                                        ->selectRaw("*")
                                        ->get();
                            if(count($tiquet) == 0) {
                                $this->tiquet->create($tiquetObject);
                            }
                        }
                    }
                });
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        
        function fromServer(){
            try{
                $idSession = $this->auth->user()->id;
                $currentDate = date("Y-m-d");
                $synchro["material"] =  $this->material->get();
                //$synchro["authToken"] =  $this->authTokens->get();
                $synchro["permission"] =  $this->permission->get();
                $synchro["roles"] =  $this->role->get();
                $synchro["role_has_permissions"] =  $this->rolePermission->get();
                $synchro["zone"] =  $this->zone->get();
                $synchro["yard"] =  $this->yard->get();
                $synchro["user"] =  $this->user->get();
                $synchro["model_has_roles"] =  $this->userRol->get();
                $synchro["third"] =  json_decode($this->client->get('/material-control/third/'.date("Y"))->getBody()->getContents());
                $synchro["tiquet"] =  $this->tiquet->where('user', $idSession)
                                                ->where('local_created_at', $currentDate)
                                                ->get();
                return $synchro;
            } catch (\Exception $e) {
                return [];
            }
        }
    }
?>