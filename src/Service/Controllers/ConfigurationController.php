<?php

namespace Denmasyarikin\Production\Service\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\ServiceOptionConfiguration;
use Denmasyarikin\Production\Service\Requests\DetailServiceOptionRequest;
use Denmasyarikin\Production\Service\Requests\CreateServiceOptionConfigurationRequest;
use Denmasyarikin\Production\Service\Requests\UpdateServiceOptionConfigurationRequest;
use Denmasyarikin\Production\Service\Requests\DeleteServiceOptionConfigurationRequest;
use Denmasyarikin\Production\Service\Transformers\ServiceOptionConfigurationListTransformer;
use Denmasyarikin\Production\Service\Transformers\ServiceOptionConfigurationDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ConfigurationController extends Controller
{
    /**
     * get list.
     *
     * @param DetailServiceOptionRequest $request
     *
     * @return json
     */
    public function getList(DetailServiceOptionRequest $request)
    {
        $serviceOption = $request->getServiceOption();

        return new JsonResponse([
            'data' => (new ServiceOptionConfigurationListTransformer($serviceOption->serviceOptionConfigurations))->toArray(),
        ]);
    }

    /**
     * create configuration.
     *
     * @param CreateServiceOptionConfigurationRequest $request
     *
     * @return json
     */
    public function createConfiguration(CreateServiceOptionConfigurationRequest $request)
    {
        $serviceOption = $request->getServiceOption();
        $this->checkIsOptionConfigurationExist($serviceOption, $request->option);

        $sequence = $serviceOption->serviceOptionConfigurations->count() + 1;
        
        $configuration = $serviceOption->serviceOptionConfigurations()->create($request->only([
            'name', 'type', 'structure',
        ]) + ['sequence' => $sequence]);

        return new JsonResponse([
            'messaage' => 'Service option configuration has been created',
            'data' => (new ServiceOptionConfigurationDetailTransformer($configuration))->toArray(),
        ], 201);
    }

    /**
     * update configuration.
     *
     * @param UpdateServiceOptionConfigurationRequest $request
     *
     * @return json
     */
    public function updateConfiguration(UpdateServiceOptionConfigurationRequest $request)
    {
        $variant = $request->getServiceOption();
        $serviceOption = $request->getServiceOption();
        $configuration = $request->getServiceOptionConfiguration();
        $this->checkIsOptionConfigurationExist($serviceOption, $request->option, $configuration);

        $configuration->update($request->only([
            'name', 'type', 'structure',
        ]));

        return new JsonResponse([
            'messaage' => 'Service option configuration has been updated',
            'data' => (new ServiceOptionConfigurationDetailTransformer($configuration))->toArray(),
        ]);
    }

    /**
     * delete configuration.
     *
     * @param DeleteServiceOptionConfigurationRequest $request
     *
     * @return json
     */
    public function deleteConfiguration(DeleteServiceOptionConfigurationRequest $request)
    {
        $configuration = $request->getServiceOptionConfiguration();
        $configuration->delete();

        return new JsonResponse(['messaage' => 'Service option configuration has been deleted']);
    }

    /**
     * check is service variant configuration exist.
     *
     * @param option                     $serviceOption
     * @param mixed                      $type
     * @param ServiceOptionConfiguration $except
     */
    protected function checkIsOptionConfigurationExist(ServiceOption $serviceOption, $type, ServiceOptionConfiguration $except = null)
    {
        $query = $serviceOption->serviceOptionConfigurations()->where('type', $type);

        if (!is_null($except)) {
            $query->where('id', '<>', $except->id);
        }

        if ($query->exists()) {
            throw new BadRequestHttpException("Service Option Configuration where {$type} already exist");
        }
    }
}
