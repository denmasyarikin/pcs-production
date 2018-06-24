<?php

namespace Denmasyarikin\Production\Service\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Production\Service\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailServiceRequest extends FormRequest
{
    /**
     * good group.
     *
     * @var Service
     */
    public $good;

    /**
     * get good.
     *
     * @return Service
     */
    public function getService(): ?Service
    {
        if ($this->good) {
            return $this->good;
        }

        $id = (int) $this->route('id');

        if ($this->good = Service::find($id)) {
            return $this->good;
        }

        throw new NotFoundHttpException('Service Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
