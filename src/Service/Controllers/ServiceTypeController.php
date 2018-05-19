<?php

namespace Denmasyarikin\Production\Service\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Production\Service\Service;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\Factories\ConfigurationManager;
use Denmasyarikin\Production\Service\Factories\ServicePriceCalculator;
use Denmasyarikin\Production\Service\Requests\DetailTypeRequest;
use Denmasyarikin\Production\Service\Requests\DetailServiceRequest;
use Denmasyarikin\Production\Service\Requests\CreateServiceTypeRequest;
use Denmasyarikin\Production\Service\Requests\UpdateServiceTypeRequest;
use Denmasyarikin\Production\Service\Requests\DeleteServiceTypeRequest;
use Denmasyarikin\Production\Service\Transformers\ServiceTypeListTransformer;
use Denmasyarikin\Production\Service\Requests\CalculateServiceTypePriceRequest;
use Denmasyarikin\Production\Service\Transformers\ServiceTypeDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ServiceTypeController extends Controller
{
    /**
     * get list.
     *
     * @param DetailServiceRequest $request
     *
     * @return json
     */
    public function getList(DetailServiceRequest $request)
    {
        $service = $request->getService();

        return new JsonResponse([
            'data' => (new ServiceTypeListTransformer($service->serviceTypes))->toArray(),
        ]);
    }

    /**
     * get detail.
     *
     * @param DetailTypeRequest $request
     *
     * @return json
     */
    public function getDetail(DetailTypeRequest $request)
    {
        $transform = new ServiceTypeDetailTransformer($request->getServiceType());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create serviceType.
     *
     * @param CreateServiceTypeRequest $request
     *
     * @return json
     */
    public function createType(CreateServiceTypeRequest $request)
    {
        $service = $request->getService();

        $serviceType = $service->serviceTypes()->create(
            $request->only(['name', 'unit_id', 'min_order', 'order_multiples'])
        );

        return new JsonResponse([
            'messaage' => 'Service type has been created',
            'data' => (new ServiceTypeDetailTransformer($serviceType))->toArray(),
        ], 201);
    }

    /**
     * update serviceType.
     *
     * @param UpdateServiceTypeRequest $request
     *
     * @return json
     */
    public function updateType(UpdateServiceTypeRequest $request)
    {
        $service = $request->getService();
        $serviceType = $request->getServiceType();

        if (true === (bool) $request->enabled) {
            if (0 === $serviceType->servicePrices()->count()) {
                throw new BadRequestHttpException('Can not be enabled with no prices');
            }
        }

        $serviceType->update($request->only(['name', 'unit_id', 'min_order', 'order_multiples', 'enabled']));

        return new JsonResponse([
            'messaage' => 'Service type has been updated',
            'data' => (new ServiceTypeDetailTransformer($serviceType))->toArray(),
        ]);
    }

    /**
     * delete serviceType.
     *
     * @param DeleteServiceTypeRequest $request
     *
     * @return json
     */
    public function deleteType(DeleteServiceTypeRequest $request)
    {
        $serviceType = $request->getServiceType();
        $serviceType->delete();

        return new JsonResponse(['messaage' => 'Service type has been deleted']);
    }

    /**
     * get configuration type list.
     *
     * @return json
     */
    public function getConfigurationTypeList()
    {
        $manager = new ConfigurationManager();
        $types = [];

        foreach ($manager->getConfigurationInstances() as $key => $configuration) {
            $types[] = [
                'type' => $key,
                'structure' => $configuration->getStructure()
            ];
        }

        return new JsonResponse(['data' => $types]);
    }

    /**
     * calculate prise.
     *
     * @param CalculateServiceTypePriceRequest $request
     *
     * @return json
     */
    public function calculatePrice(CalculateServiceTypePriceRequest $request)
    {
        $serviceType = $request->getServiceType();

        $calculator = new ServicePriceCalculator($serviceType);

        try {
            $calculation = $calculator->calculatePrice(
                $request->quantity,
                $request->input('value'),
                $request->input('chanel_id')
            );

            return new JsonResponse([
                'quantity' => $calculation->getQuantity(),
                'unit_price' => $calculation->getUnitPrice(),
                'unit_total' => $calculation->getUnitTotal(),
                'configurations' => $calculation->getConfigurations()
            ]);
        } catch (\Exception $e){
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
