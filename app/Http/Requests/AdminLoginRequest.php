<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'userid' => 'required',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'userid' => 'Please provide a userid.',
            'password' => 'Please provide a password.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getCredentials()
    {
        // The form field for providing userid or password
        // have name of "userid", however, in order to support
        // logging users in with both (userid and email)
        // we have to check if user has entered one or another
        $userid = $this->get('userid');

        return [
            'usercode' =>$this->get('userid'),
            'password' => $this->get('password')
        ];

        // if ($this->isEmail($userid)) {
        //     return [
        //         'email' => $userid,
        //         'password' => $this->get('password')
        //     ];
        // }
        // return $this->only('userid', 'password');

       
    }

    /**
     * Validate if provided parameter is valid email.
     *
     * @param $param
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    // private function isEmail($param)
    // {
    //     $factory = $this->container->make(ValidationFactory::class);

    //     return ! $factory->make(
    //         ['userid' => $param],
    //         ['userid' => 'email']
    //     )->fails();
    // }
}
