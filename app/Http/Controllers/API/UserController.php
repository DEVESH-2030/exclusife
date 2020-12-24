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


class UserController extends Controller
{
    // status for error and success
    public $successStatus = '200';
    public $failedStatus = '0';
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
    //     $admindetail=LoginUser::where('mobile',$mobile)->first();
    //     $dbmobile=$admindetail->mobile;
    //     $dbpassword=$admindetail->password;

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
    //          if($mobile==$dbmobile)
    //         {
    //             if($dbpassword==$password)
    //             {
    //                 $otp = rand('1111','9999');
    //                 //counting that mobile occur only once neither more nor 0
    //                 $countMobileNo = LoginUser::where('mobile',$mobile)->where('active','enable')->count();

    //                 if($countMobileNo==1)
    //                 {
    //                     //getting mobile number from db
    //                     $userMobileData = LoginUser::where('mobile',$mobile)->first();
    //                    //updating otp in database
    //                          $updateOtp = LoginUser::where('mobile',$mobile)->update(['otp' => $otp]);
    //                    if($updateOtp) {
    //                        //otp success message
    //                        return response()->json([
    //                       'responseMessage'         => 'otp send on your mobile no.',
    //                       'responseCode'            =>  $this->successStatus,
    //                       'mobile'                  =>  $userMobileData->mobile,
    //                       'otp'                     =>  (string)$otp,
    //                      ]);
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





     # ------  Api for Send SMS on user Mobile -------
    public function sendSms($mobile)
    {
        try
        {
            $curl = curl_init();
            $rand = rand('1111','9999');
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
    public function otpverification(Request $request)
    {
        $otp=$request->otp;

        $admindetail=LoginUser::where('otp',$otp)->first();

        if(empty($otp)){
            return response()->json([
                'responseMessage'         => 'please enter your otp',
                'responseCode'            =>  $this->failedStatus,

               ]);

        }else{
            if($admindetail){
                return response()->json([
                    'responseMessage'         => 'otp verified successfully',
                    'responseCode'            =>  $this->successStatus,
                    'data'                    => $otp,
                   ]);
            }else{
                return response()->json([
                    'responseMessage'         => 'otp varification fail',
                    'responseCode'            =>  $this->failedStatus,

                   ]);
            }
        }


    }

    # ---Registration API ---
    public function register(Request $request) 
    { 
        # use validatior and make request
        // $validator = Validator::make($request->all(), 
        //     [ 
        //         'c_password'    => 'required|same:password',
        //     ]);
        # request data from database 
        $name       = $request->name;
        $email      = $request->email;
        $password   = $request->password;
        $mobile     = $request->mobile;
        $gender     = $request->gender;
        $dob        = $request->dob;
        $anniversary = $request->anniversary;
        $comment    = $request->comment;

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
        }

        # validator fail
        // if($validator->fails())
        // { 
        //     return response()->json(['error'=>$validator->errors()], 401);            
        // }

        # Create object of use
        $user = new User;
        # store data into user database
        $user->name     =$name;
        $user->email    =$email;
        $user->password =$password;
        $user->mobile   =$mobile;
        $user->gender   =$gender;
        $user->dob      =$dob;
        $user->anniversary =$anniversary;
        $user->comment     =$comment;
        # request input data  
        $input = $request->all();
        # input password make into token             
        $input['password'] = Hash::make($request->password);
        # count email
        $countUser = User::where(['email'=> $email])->count();   #email for already exixst
        if ($countUser == 0) {
            $user = User::create($input);
        # token generate for password     
        $success['Token Password'] =  $user->createToken('MyApp')->accessToken; 
        # save all data User table into databse
        $user->save();
        # get data into array 
        $data[] = [
                'id'    => (String)$user->id ?? '',
                'name'  => $user->name ?? '',
                'email' => $user->email ?? '',
                'mobile'=> $user->mobile ?? '',
                'password'  =>$user->password ?? '',
                'token' => $user->createToken('MyApp')->accessToken,                       
        ];
        # give rosponse success  
            return response()->json([
                'responseMessage'   =>'Registraion Successfully',
                'responseCode'      => $this->successStatus,
                'result'            => $data
            ]); 
        }
        # give rasponse if any error
        else {
            return response()->json([
                'responseMessage'   =>'This email already registerd',
                'responseCode'      => $this->failedStatus,
                'result'            => ''
            ]); 
        }
    }
    # ------ End register ------


    //add customer into customer list
    public function AddCustomer(Request $request){
        //validating one of twi field at a time
        $validator=validator::make($request->all(),[
            'mobile_no' => 'required_without:email',
            'email'     => 'required_without:mobile_no',
        ]);
        //validation failed then show error message
        if ($validator->fails()) {
            return response()->json([
                ['responseMessage'  => $validator->errors()],
                'responseCode'     =>  $this->failedStatus,
            ]);

        }
        //storing data in database
        $contact = Customer::create($request->all());
        //getting success response when data stored in database
        if(empty($contact)){
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

    //view all data of customers
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

    // create new requets of customer
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
            //taking data from customer and inserting in database column
            $createrequest= new CreateRequest;
            $createrequest->mobile_no=$request->mobile_no;
            $createrequest->product_inquired=$request->product_inquired;
            $createrequest->customer_price_expectation=$request->customer_price_expectation;
            $createrequest->expected_date=$request->expected_date;
            $createrequest->request_text=$request->request_text;
            $createrequest->image_file=$request->image_file;
            $storeddata=$createrequest->save();
             //response message after submission
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
}
