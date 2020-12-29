<?php
namespace App\Http\Controllers\API;

#use Database
use DB;
# use model
use App\User;
# use validator
use Validator;
# use customer model
use App\Customer;
# use loginuser model 
use App\LoginUser;
# use calllog model
use App\Models\CallLogs;
# use upcoming dob
use App\Models\UpcomingDOB;
# use createrequest model
use App\CreateRequest;
# use request paskage 
use Illuminate\Http\Request;
# use controller 
use App\Http\Controllers\Controller;
# use user resource 
use App\Http\Resources\UserResource;
# use authentication 
use Illuminate\Support\Facades\Auth;
# use hash package for generate pass
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;



class UserController extends Controller
{
    // status for error and success
    protected $successStatus = '200';
    protected $failedStatus = '0';

    protected $user;
    protected $customer;
    protected $loginuser;
    protected $createrequest;
    protected $calllog;
    protected $upcomingdob

    function __construct(User $user, LoginUser $loginuser, Customer $customer, CreateRequest $createrequest, CallLogs $calllog, UpcomingDOB $upcomingdob)
    {
        $this->user         = $user;
        $this->customer     = $customer;
        $this->loginuser    = $loginuser;
        $this->createrequest= $createrequest;
        $this->calllog      = $calllog;
        $this->upcomingdob  = $upcomingdob;
    }
/**
     * login api
     *
     * @return \Illuminate\Http\Response
     */

    #login api
    // public function login(Request $request)
    // {
    //     //reuesting information from users
    //     $mobile = $request->mobile;
    //     $password=$request->password;
    //     // dd($password);
    //     $admindetail=User::where('mobile',$mobile)->first();
    //     $dbmobile=$admindetail->mobile;
    //     $dbpassword=$admindetail->password;
    //     // dd($dbpassword);
    //     //checking if it is emoty or not
    //     if(empty($mobile))
    //     {
    //         return response()->json([
    //             'responseMessage'         => 'mobile is required',
    //             'responseCode'            =>  $this->failedStatus,
    //            ]);
    //     }elseif(empty($password)){
    //         return response()->json([
    //             'responseMessage'         => 'password is required',
    //             'responseCode'            =>  $this->failedStatus,
    //            ]);

    //     }else{
    //                 // dd($password);

    //          if($mobile==$dbmobile)
    //         {   
    //             # check password here 
    //             if(Hash::check($request->$dbpassword, $dbpassword->$password) )
    //             {   
    //                 // dd($dbpassword->$password);
    //                 $success['Token Password'] =  $dbpassword->createToken('MyApp')->accessToken; 
    //                 dd($success);
    //                 $otp = rand('1111','9999');
    //                 //counting that mobile occur only once neither more nor 0
    //                 $countMobileNo = User::where('mobile',$mobile)->where('active','enable')->count();

    //                 if($countMobileNo==1)
    //                 {
    //                     //getting mobile number from db
    //                     $userMobileData = User::where('mobile',$mobile)->first();
    //                    //updating otp in database
    //                          $updateOtp = User::where('mobile',$mobile)->update(['otp' => $otp]);
    //                    if($updateOtp) {
    //                         // if(Hash::check($request->password, $dbpassword->password))
    //                         // {
    //                             //otp success message
    //                             return response()->json([
    //                             'responseMessage'         => 'otp send on your mobile no.',
    //                             'responseCode'            =>  $this->successStatus,
    //                             'mobile'                  =>  $userMobileData->mobile,
    //                             'otp'                     =>  (string)$otp,
    //                             ]);
    //                         // }
    //                    }
    //                    else{
    //                        //error when otp sending is fail
    //                      return response()->json([
    //                     'responseMessage'         => 'otp sending failed',
    //                     'responseCode'            =>  $this->failedStatus,
    //                      ]);
    //                    }
    //                 }
    //             }else{
    //                 return response()->json([
    //                     'responseMessage'         => 'password is incorrect',
    //                     'responseCode'            =>  $this->failedStatus,
    //                    ]);
    //               }
    //         }else{
    //             return response()->json([
    //                 'responseMessage'         => 'mobile no. not registered',
    //                 'responseCode'            =>  $this->failedStatus,
    //                ]);
    //         }
    //            //genrating random otp
    //     }
    // }
    # End here login

    
     # ------ Login APi -------
    public function login(Request $request)
    {   
        # request to email and password
        // $email    = $request->email;
        $mobile    = $request->mobile;
        $password = $request->password;
        # if empty data give response 
        if(empty($mobile))
        {
            return response()->json([
                'responseMessage'   => 'please enter your mobile',
                'responseCode'      => $this->failedStatus,
            ]);
        }elseif (empty($password)) {
                return response()->json([
                    'responseMessage'=> 'please enter your password',
                    'responseCode'   => $this->failedStatus,
                ]);
        }
        # get email first
        $loginuser = LoginUser::where(['mobile'=> $mobile])->first();
        if($loginuser)
        {
            # check epassword and then create token pass..     
            if(Hash::check($request->password, $loginuser->password))
            {
                // dd($loginuser->password);
                $mobile = $loginuser->mobile;
                $otp = rand('1000','9999');
                // $otp = $this->sendSms($mobile);
                $updateotp = LoginUser::where('mobile',$mobile)->update(['otp' => $otp]);
                $loginuser->save();
                if($updateotp)
                {
                    # get data in array
                    $data[] = [

                        'id'          => (String)$loginuser->id ?? '',
                        'mobile'      => $loginuser->mobile ?? '',
                        'otp'         => $otp ?? '',
                    ];
                    # give response success
                    return response()->json([
                        'responseMessage' => 'Send otp on your mobile successfully', 
                        'responseCode'    => $this->successStatus,
                        'result'          =>$data,
                    ]);
                }
            } 
            # give response if wrong password
            else {
                return response()->json([
                    'responseMessage'   => 'Wrong password',
                    'responseCode'      => $this->failedStatus,
                ]);                
            }
        }
        # give response if wrong number
        else {
            return response()->json([
                'responseMessage'   => 'This number is not registerd',
                'responseCode'      => $this->failedStatus,
            ]);
        }
    }
    #  ------- End Login ------



     # ------  Api for Send SMS on user Mobile -------
    public function sendSms($mobile)
    {
        try
        {
            $curl = curl_init();
            $rand = rand('1000','9999');
            $message = $mobile.' OPT for verify your MObile Number'.$rand ;
            curl_setopt_array($curl, array(
            CURLOPT_URL             => "http://2factor.in/API/V1/1064bf98-02a9-11eb-9fa5-0200cd936042/SMS/$mobile/$rand",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "GET",
            CURLOPT_POSTFIELDS      => "",
            CURLOPT_HTTPHEADER      => array("content-type: application/x-www-form-urlencoded"),));
            $response = curl_exec($curl);
            //print_r($response); exit;
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) 
            {
                return $err;
            }else {
                return $rand;
            }
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        } 
    } 
    #  -------  End SendSMS Api completed -------


    # OTP Verification 
    public function otpverificationForLogin(Request $request)
    {
        $otp=$request->otp;

        $verify_otp=LoginUser::where('otp',$otp)->first();

        if(empty($otp)){
            return response()->json([
                'responseMessage'         => 'please enter your otp',
                'responseCode'            =>  $this->failedStatus,

               ]);

        }else {

            $verify = LoginUser::where('otp',$request->otp)->first();
            if($verify){   
                $data = LoginUser::select('id')->where('otp',$request->otp)->first();
                $id=$data['id'];
                $verify->verify_otp = 1;
                $verify->update();
                $dataArray[] = [
                    'mobile'        => $verify_otp->mobile ?? '',
                    'password'      => $verify_otp->password ?? '',
                    'active_data'   => $verify_otp->active ?? ''
                ];
                // dd($data1);
                return response()->json([
                    'responseMessage'       => 'Login successfuly!',
                    'responseCode'          => $this->successStatus, 
                    'verified_id'           => $verify->id,
                    'verified:'             => $dataArray
                ]);
            }
            
            elseif (!$verify_otp = $verify_otp) {
            return response()->json([
                'responseMessage'         => 'incorrect otp',
                'responseCode'            =>  $this->failedStatus,

               ]);
            }else{
                return response()->json([
                    'responseMessage'         => 'otp varification fail',
                    'responseCode'            =>  $this->failedStatus,

                   ]);
            }
        }
    }

        #-------------


    # ---Registration API ---
    public function AddnRegisternewCustomer(Request $request) 
    { 
        # request data from database 
        $name       = $request->name;
        $email      = $request->email;
        $password   = $request->password;
        $mobile     = $request->mobile;
        $gender     = $request->gender;
        $dob        = $request->dob;
        $anniversary= $request->anniversary;
        $comment    = $request->comment;
        $status     = $request->status;

        # if you does not input/enter any data then give response here different type as.   
        if(empty($name))
        {
            return response()->json([
                'responseMessage'   => 'please enter user name',
                'responseCode'      => $this->failedStatus,
            ]);
        }elseif (empty($email)) {
            return response()->json([
                'responseMessage'   => 'please enter user email',
                'responseCode'      => $this->failedStatus,
            ]);
        }elseif (empty($password)) {
            return response()->json([
                'responseMessage'   => 'please enter user password',
                'responseCode'      => $this->failedStatus,              

            ]);
        }elseif(empty($mobile)) {
            return response()->json([
                'responseMessage'   => 'please enter mobile',
                'responseCode'      => $this->failedStatus,
            ]);
        }elseif(empty($gender)) {
            return response()->json([
                'responseMessage'   => 'please enter gender (m,f)',
                'responseCode'      => $this->failedStatus,
            ]);
        }elseif(empty($dob)) {
            return response()->json([
                'responseMessage'   => 'please enter date of birth',
                'responseCode'      => $this->failedStatus,
            ]);
        }elseif (empty($anniversary)) {
            return response()->json([
                'responseMessage'   => 'please enter anniversary date ',
                'responseCode'      => $this->failedStatus,
            ]);           
        }elseif (empty($comment)) {
            return response()->json([
                'responseMessage'   => 'please enter comment',
                'responseCode'      => $this->failedStatus,
            ]);           
        }elseif (empty($status)) {
            return response()->json([
                'responseMessage'   => 'please enter status',
                'responseCode'      => $this->failedStatus,
            ]);           
        }
        # Create object of use
        $user = new User;
        # store data into user database
        $user->name         =$name;
        $user->email        =$email;
        $user->password     =Hash::make($password);
        $user->mobile       =$mobile;
        $user->gender       =$gender;
        $user->dob          =$dob;
        $user->anniversary  =$anniversary;
        $user->comment      =$comment;
        $user->status       =$status;
        
        $countUser = User::where(['email'=> $email])->count();   #email for already exixst
        if ($countUser == 0) {
            # token generate for password     
            $user->save();
            $userToken =  $user->createToken('MyApp')->accessToken; 
            # save all data User table into databse
            # get data into array 
            $data = [
                    'id'        => (String)$user->id ?? '',
                    'name'      => $user->name ?? '',
                    'email'     => $user->email ?? '',
                    'mobile'    => $user->mobile ?? '',
                    'password'  => $user->password ?? '',
                    'token'     => $userToken,                       
            ];
            # give rosponse success  
            return response()->json([
                'responseMessage'   =>'Add/Register new customer Successfully',
                'responseCode'      => $this->successStatus,
                'result'            => $data
            ]); 
        }
        # give rasponse if any error
        else {
            return response()->json([
                'responseMessage'   =>'This email already registerd',
                'responseCode'      => $this->failedStatus,
            ]); 
        }
    }
    # ------ End register ------


    //add customer into customer list
    public function AddCustomer(Request $request)
    {
        //validating one of twi field at a time
        // $validator=validator::make($request->all(),[
        //     'mobile_no' => 'required_without:email',
        //     'email'     => 'required_without:mobile_no',
        // ]);
        // //validation failed then show error message
        // if ($validator->fails()) {
        //     return response()->json([
        //         ['responseMessage'  => $validator->errors()],
        //         'responseCode'     =>  $this->failedStatus,
        //     ]);

        // }
        $mobile     = $request->mobile_no; 
        $email      = $request->email; 
        $avtive     = $request->active; 

        if(empty($mobile))
        {
            return response()->json([
                'responseMessage'   => 'please enter mobile number',
                'responseCode'      => $this->failedStatus,
            ]);
        }elseif (empty($email)) {
           return response()->json([
                'responseMessage'   => 'please enter email',
                'responseCode'      => $this->failedStatus,
            ]);
        }elseif (empty($status)) {
           return response()->json([
                'responseMessage'   => 'please enter status',
                'responseCode'      => $this->failedStatus,
            ]);
        }
        #storing data in database
        $contact = Customer::create($request->all());
        // dd($contact->mobile_no);
        //getting success response when data stored in database
        if($contact){
            return response()->json([
                'responseMessage'  => 'data successfully stored',
                'responseCode'     =>  $this->successStatus,
                'data'             => $contact,
           ]);
        }else{
            return response()->json([
                'responseMessage'  => 'data not entered',
                'responseCode'     =>  $this->failedStatus,
                'data'             =>  [],
               ]);
            }
     }

    # view all data of customers
    public function ViewCustomer()
    {
        $customer = Customer::all();
        if(empty($customer)){
            return response()->json([
                'responseMessage' => 'data successfully fetched',
                'responseCode'    =>  $this->successStatus,
                'data'            =>  $customer,
           ]);
        }else{
            return response()->json([
                'responseMessage' => 'there is no data',
                'responseCode'    =>  $this->failedStatus,
                'data'            =>  [],
            ]);
        }
    }
    #End here 

    # create new requets of customer
    public function CreateRequest(Request $request)
    {
        //validating the required fields
        $validator = Validator::make($request->all(), [
            'mobile_no'     => 'required|max:10',
            'expected_date' => 'required',
            'request_text'  => 'required|min:10',
        ]);

         //if validation failed
        if ($validator->fails()) {
            return response()->json([
                ['responseMessage' => $validator->errors()],
                'responseCode'     =>  $this->successStatus,
               ]);
        }
            # taking data from customer and inserting in database column
            $createrequest= new CreateRequest;
            $createrequest->mobile_no=$request->mobile_no;
            $createrequest->product_inquired=$request->product_inquired;
            $createrequest->customer_price_expectation=$request->customer_price_expectation;
            $createrequest->expected_date=$request->expected_date;
            $createrequest->request_text=$request->request_text;
            $createrequest->image_file=$request->image_file;
            $storeddata=$createrequest->save();
            # response message after submission
             if(!empty($storeddata))
             {
                 return response()->json([
                    'responseMessage' => 'Request Created Successfully',
                    'responseCode'    =>  $this->successStatus,
                    'data'            =>  $createrequest,
               ]);
            }else{
                return response()->json([
                    'responseMessage' => 'data not entered',
                    'responseCode'    =>  $this->failedStatus,
                    'data'            =>  [],
                   ]);

            }

    }

    # Api for Whitelist detail of customers
    public function whiteList(Request $request)
    {   
        $whitelistDetails = $this->user::get();
        $whitelist = [];
        foreach ($whitelistDetails as $data) 
        {
            $whitelist[] = [
                'id'        => $data->id ?? '',
                'name'      => $data->name ?? '',
                'mobile'    => $data->mobile ?? '',
                'gender'    => $data->gender ?? '',
            ]; 
        }
        return response()->json([
            'responseMessage'   => 'Display Whitelisted customres ',
            'responseCode'      => $this->successStatus,
            'result'            => $whitelist,
        ]);
    }
    # End here

    # Count Total Customers
    public function countTotalcustomres(Request $request)
    {   
        $total = $this->user::count();
        $whitelistDetails = $this->user::get();
        $whitelist = [];
        foreach ($whitelistDetails as $data) 
        {
            $whitelist[] = [
                'id'        => $data->id ?? '',
                'mobile'    => $data->mobile ?? '',
                'status'    => $data->status ?? '',
            ]; 
        }
        return response()->json([
            'responseMessage'   => 'Count Total customers',
            'responseCode'      => $this->successStatus,
            'total'             => $total,
            'result'            => $whitelist,
        ]);
    }
    # End here

    # api for call log detials
    public function callLog(Request $request)
    {
        //
    }
    # End here 

    public function upcomingDateofBirth(Request $request)
    {

    }
}
