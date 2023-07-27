<?php

namespace App\Http\Controllers;
use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "packages";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $packages = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.list", compact("errors", $errors));
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.list", compact("errors", $errors));
        }
        return view("packages.list", compact("packages", $packages));
    }

    public function showNew()
    {
        $modules = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "modules";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $modules = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.add", compact("errors", $errors));
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.add", compact("errors", $errors));
        }
        return view("packages.add",compact('modules'));
    }

    public function addNew(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "description" => "required|string",
            "is_active" => "required|int",
            "display_order" => "required|int",
            "applicable_for" => "required|int",
            "show_on" => "required|array",
            "modules" => "required|array",
            "currency_code" => "required|string",
            "base_rate_monthly_billed" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "base_rate_quarterly_billed" => "required|regex:/^\d*(\.\d{1,6})?$/",
            "base_rate_half_yearly_billed" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "base_rate_yearly_billed" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "call_rate_per_minute" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_sms" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_did" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_fax" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_email" => "required|regex:/^\d*(\.\d{1,5})?$/",
        ]);

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "package";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");

            if ($response->success) {
                session()->flash("success", "Package added");
                return redirect('super/packages');
            } else {
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    public function view(Request $request, string $id)
    {
        $modules = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "modules";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $modules = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.view", compact("errors", $errors));
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.view", compact("errors", $errors));
        }

        $package = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "package/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"])
            {
                $package = $response["data"];
            }
            else
            {
                foreach ( $response["errors"] as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.view")->withErrors($errors);
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.view")->withErrors($errors);
        }
        return view("packages.view",compact('package','modules'));
    }

    public function show(Request $request, string $id)
    {
        $modules = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "modules";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $modules = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.edit", compact("errors", $errors));
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.edit", compact("errors", $errors));
        }

        $package = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "package/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"])
            {
                $package = $response["data"];
            }
            else
            {
                foreach ( $response["errors"] as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.edit")->withErrors($errors);
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.edit")->withErrors($errors);
        }
        return view("packages.edit",compact('package','modules'));
    }

    public function update(Request $request, string $key)
    {
        $this->validate($request, [
            "name" => "required|string",
            "description" => "required|string",
            "is_active" => "required|int",
            "display_order" => "required|int",
            "applicable_for" => "required|int",
            "show_on" => "required|array",
            "modules" => "required|array",
            "currency_code" => "required|string",
            "base_rate_monthly_billed" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "base_rate_quarterly_billed" => "required|regex:/^\d*(\.\d{1,6})?$/",
            "base_rate_half_yearly_billed" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "base_rate_yearly_billed" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "call_rate_per_minute" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_sms" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_did" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_fax" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_email" => "required|regex:/^\d*(\.\d{1,5})?$/",
        ]);
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "package/$key";
            $response = Helper::PostApi($url, $this->getBuildBody($request));
            if (!$response->success) {
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", "Package updated");
        return redirect('super/packages');
    }


    public function updateRate(Request $request, int $id)
    {
        $this->validate($request, [
            "package_name" => "required|string",
            "call_rate_per_minute" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_six_by_six_sec" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_sms" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_did" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_fax" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_email" => "required|regex:/^\d*(\.\d{1,5})?$/",
        ]);
        $errors = new MessageBag();
        try {

            //echo "<pre>";print_r($this->getBuildBody1($request));die;
            $url = env('API_URL') . "rate/$id";
            $response = Helper::PostApi($url, $this->getBuildBody1($request));

           // echo "<pre>";print_r($response);die;
            if (!$response->success) {
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", "Rate updated");
        return redirect('super/package/rate/edit/'.$id);
    }

    public function copy(Request $request, string $id)
    {
        $modules = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "modules";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $modules = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
            //return view("packages.copy", compact("errors", $errors));
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.copy", compact("errors", $errors));
        }

        $package = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "package/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"])
            {
                $package = $response["data"];
            }
            else
            {
                foreach ( $response["errors"] as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.copy")->withErrors($errors);
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.copy")->withErrors($errors);
        }
        return view("packages.copy",compact('package','modules'));
    }


    public function rate(Request $request, string $id)
    {

        $country_wise_rate = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "country-wise-rate/$id";
            $response = Helper::GetApi($url, [], true);
            //echo "<pre>";print_r($response);die;
            if ($response["success"])
            {
                $country_wise_rate = $response["data"];
            }
            else
            {
                foreach ( $response["errors"] as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.rate_list")->withErrors($errors);
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.rate_list")->withErrors($errors);
        }
        

        $package = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "package/$id";
            $response = Helper::GetApi($url, [], true);
            //echo "<pre>";print_r($response);die;
            if ($response["success"])
            {
                $package = $response["data"];
            }
            else
            {
                foreach ( $response["errors"] as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.rate_list")->withErrors($errors);
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.rate_list")->withErrors($errors);
        }
        return view("packages.rate_list",compact('package','country_wise_rate'));
    }

    public function addRate()
    {
        /* Phone Country list */
        $country_list = [];
        $url = env('API_URL').'phone-country-list';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success)
            {
                $phone_country = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }

        $packages = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "packages";
        try
        {
            $response = Helper::GetApi($url);
           // echo "<pre>";print_r($response);die;
            if ($response->success)
            {
                $packages = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.add_rate", compact("errors", $errors));
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.add_rate", compact("errors", $errors));
        }
        return view("packages.add_rate",compact('packages','phone_country'));
    }

    public function addNewRate(Request $request)
    {

        //dd($request);
        /*$this->validate($request, [
            "package_name" => "required|string",
            "country_code" => "required",
            "call_rate_per_minute" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_six_by_six_sec" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_sms" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_did" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_fax" => "required|regex:/^\d*(\.\d{1,5})?$/",
            "rate_per_email" => "required|regex:/^\d*(\.\d{1,5})?$/",
        ]);*/

        //$errors = new MessageBag();
        try {
             $body = array(
            'parameter' => $request->all(),
        );
            //echo "<pre>";print_r($body);die;




            $url = env('API_URL') . "add-rate";
            $response = Helper::RequestApi($url, "PUT", $request->all(), "json");

           // $response = Helper::PostApi($url, $body);
          //  echo "<pre>";print_r($response);die;

            if ($response->success) {
                session()->flash("success", "Country Wise rate added");
                return redirect('super/package/rate/'.$request->package_name);
            } else {
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }


    public function editShow(Request $request, int $id)
    {
        $rate = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "rate/$id";
            $response = Helper::GetApi($url, [], true);
            //echo "<pre>";print_r($response);die;
            if ($response["success"])
            {
                $rate = $response["data"];
            }
            else
            {
                foreach ( $response["errors"] as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.edit_rate")->withErrors($errors);
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.edit_rate")->withErrors($errors);
        }


        $packages = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "packages";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $packages = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("packages.edit_rate", compact("errors", $errors));
            }
        }
        catch (\Throwable $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("packages.edit_rate", compact("errors", $errors));
        }

           // echo "<pre>";print_r($modules);die;

        /* Phone Country list */
        $country_list = [];
        $url = env('API_URL').'phone-country-list';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success)
            {
                $phone_country = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }


        
        return view("packages.edit_rate",compact('rate','packages','phone_country'));
    }

    private function getBuildBody(Request $request)
    {
        $body = [
            "name" => trim($request->get("name")),
            "description" => trim($request->get("description")),
            "is_active" => $request->get("is_active"),
            "display_order" => $request->get("display_order"),
            "applicable_for" => $request->get("applicable_for"),
            "show_on" => $request->get("show_on"),
            "modules" => $request->get("modules"),
            "currency_code" => $request->get("currency_code"),
            "base_rate_monthly_billed" => $request->get("base_rate_monthly_billed"),
            "base_rate_quarterly_billed" => $request->get("base_rate_quarterly_billed"),
            "base_rate_half_yearly_billed" => $request->get("base_rate_half_yearly_billed"),
            "base_rate_yearly_billed" => $request->get("base_rate_yearly_billed"),
            "call_rate_per_minute" => $request->get("call_rate_per_minute"),
            "rate_per_sms" => $request->get("rate_per_sms"),
            "rate_per_did" => $request->get("rate_per_did"),
            "rate_per_fax" => $request->get("rate_per_fax"),
            "rate_per_email" => $request->get("rate_per_email"),
            "free_call_minute_monthly" => $request->get("free_call_minute_monthly"),
            "free_sms_monthly" => $request->get("free_sms_monthly"),
            "free_fax_monthly" => $request->get("free_fax_monthly"),
            "free_emails_monthly" => $request->get("free_emails_monthly"),
            "free_did_monthly" => $request->get("free_did_monthly"),


        ];

        return $body;
    }

    private function getBuildBody1(Request $request)
    {
        $body = [
            "package_name" => trim($request->get("package_name")),
            "country_code" => trim($request->get("country_code")),
            "call_rate_per_minute" => $request->get("call_rate_per_minute"),
            "rate_six_by_six_sec" => $request->get("rate_six_by_six_sec"),
            "rate_per_sms" => $request->get("rate_per_sms"),
            "rate_per_did" => $request->get("rate_per_did"),
            "rate_per_fax" => $request->get("rate_per_fax"),
            "rate_per_email" => $request->get("rate_per_email"),


        ];

        return $body;
    }
}
