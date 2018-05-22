<?php

namespace Denmasyarikin\Production\Service\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;
use Denmasyarikin\Production\Service\Factories\ServicePriceCalculator;
use Denmasyarikin\Production\Service\Requests\DetailServiceTypeRequest;
use Denmasyarikin\Production\Service\Requests\CreateServiceTypeConfigurationRequest;
use Denmasyarikin\Production\Service\Requests\UpdateServiceTypeConfigurationRequest;
use Denmasyarikin\Production\Service\Requests\DeleteServiceTypeConfigurationRequest;
use Denmasyarikin\Production\Service\Requests\CalculateServiceTypeConfigurationPriceRequest;
use Denmasyarikin\Production\Service\Transformers\ServiceTypeConfigurationListTransformer;
use Denmasyarikin\Production\Service\Transformers\ServiceTypeConfigurationDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ConfigurationController extends Controller
{
    /**
     * get list.
     *
     * @param DetailServiceTypeRequest $request
     *
     * @return json
     */
    public function getList(DetailServiceTypeRequest $request)
    {
        $serviceType = $request->getServiceType();

        return new JsonResponse([
            'data' => (new ServiceTypeConfigurationListTransformer($serviceType->serviceTypeConfigurations))->toArray(),
        ]);
    }

    /**
     * create configuration.
     *
     * @param CreateServiceTypeConfigurationRequest $request
     *
     * @return json
     */
    public function createConfiguration(CreateServiceTypeConfigurationRequest $request)
    {
        $serviceType = $request->getServiceType();
        $this->checkIsTypeConfigurationExist($serviceType, $request->type);

        $configuration = $serviceType->serviceTypeConfigurations()->create($request->only([
            'name', 'type', 'structure'
        ]));

        return new JsonResponse([
            'messaage' => 'Service type configuration has been created',
            'data' => (new ServiceTypeConfigurationDetailTransformer($configuration))->toArray(),
        ], 201);
    }

    /**
     * update configuration.
     *
     * @param UpdateServiceTypeConfigurationRequest $request
     *
     * @return json
     */
    public function updateConfiguration(UpdateServiceTypeConfigurationRequest $request)
    {
        $variant = $request->getServiceType();
        $serviceType = $request->getServiceType();
        $configuration = $request->getServiceTypeConfiguration();
        $this->checkIsTypeConfigurationExist($serviceType, $request->type, $configuration);

        $configuration->update($request->only([
            'name', 'type', 'structure'
        ]));

        return new JsonResponse([
            'messaage' => 'Service type configuration has been updated',
            'data' => (new ServiceTypeConfigurationDetailTransformer($configuration))->toArray(),
        ]);
    }

    /**
     * delete configuration.
     *
     * @param DeleteServiceTypeConfigurationRequest $request
     *
     * @return json
     */
    public function deleteConfiguration(DeleteServiceTypeConfigurationRequest $request)
    {
        $configuration = $request->getServiceTypeConfiguration();
        $configuration->delete();

        return new JsonResponse(['messaage' => 'Service type configuration has been deleted']);
    }

    /**
     * check is service variant configuration exist.
     *
     * @param type                     $serviceType
     * @param mixed                    $type
     * @param ServiceTypeConfiguration $except
     */
    protected function checkIsTypeConfigurationExist(ServiceType $serviceType, $type, ServiceTypeConfiguration $except = null)
    {
        $query = $serviceType->serviceTypeConfigurations()->where('type', $type);

        if (!is_null($except)) {
            $query->where('id', '<>', $except->id);
        }

        if ($query->exists()) {
            throw new BadRequestHttpException("Service Type Configuration where {$type} already exist");
        }
    }

    /**
     * calculate price
     *
     * @param CalculateServiceTypeConfigurationPriceRequest $request
     * @return json
     */
    public function calculatePrice(CalculateServiceTypeConfigurationPriceRequest $request)
    {
        $serviceType = $request->getServiceType();
        $serviceTypeConfigurations = $request->getServiceTypeConfiguration();

        $calculator = new ServicePriceCalculator($serviceType);

        try {
            $calculation = $calculator->calculatePrice(
                $request->quantity,
                $request->input('value'),
                $request->input('chanel_id'),
                $serviceTypeConfigurations
            );

            return new JsonResponse([
                'quantity' => $calculation->getQuantity(),
                'unit_price' => $calculation->getUnitPrice(),
                'unit_total' => $calculation->getUnitTotal(),
                'configurations' => $calculation->getConfigurations(),
            ]);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
