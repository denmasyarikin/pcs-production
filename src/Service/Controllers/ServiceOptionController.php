<?php

namespace Denmasyarikin\Production\Service\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Production\Service\Service;
use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\Factories\ConfigurationManager;
use Denmasyarikin\Production\Service\Factories\ServicePriceCalculator;
use Denmasyarikin\Production\Service\Requests\DetailOptionRequest;
use Denmasyarikin\Production\Service\Requests\DetailServiceRequest;
use Denmasyarikin\Production\Service\Requests\CreateServiceOptionRequest;
use Denmasyarikin\Production\Service\Requests\UpdateServiceOptionRequest;
use Denmasyarikin\Production\Service\Requests\DeleteServiceOptionRequest;
use Denmasyarikin\Production\Service\Requests\UpdateSortingServiceOptionRequest;
use Denmasyarikin\Production\Service\Transformers\ServiceOptionListTransformer;
use Denmasyarikin\Production\Service\Requests\CalculateServiceOptionPriceRequest;
use Denmasyarikin\Production\Service\Transformers\ServiceOptionDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ServiceOptionController extends Controller
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
        $options = $service->serviceOptions();

        if ($request->has('name')) {
            $options->where('name', 'like', "%{$request->name}%");
        }

        return new JsonResponse([
            'data' => (new ServiceOptionListTransformer($options->get()))->toArray(),
        ]);
    }

    /**
     * get detail.
     *
     * @param DetailOptionRequest $request
     *
     * @return json
     */
    public function getDetail(DetailOptionRequest $request)
    {
        $transform = new ServiceOptionDetailTransformer($request->getServiceOption());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create serviceOption.
     *
     * @param CreateServiceOptionRequest $request
     *
     * @return json
     */
    public function createOption(CreateServiceOptionRequest $request)
    {
        $service = $request->getService();

        $serviceOption = $service->serviceOptions()->create(
            $request->only(['name', 'unit_id', 'min_order', 'order_multiples'])
        );

        return new JsonResponse([
            'messaage' => 'Service option has been created',
            'data' => (new ServiceOptionDetailTransformer($serviceOption))->toArray(),
        ], 201);
    }

    /**
     * update serviceOption.
     *
     * @param UpdateServiceOptionRequest $request
     *
     * @return json
     */
    public function updateOption(UpdateServiceOptionRequest $request)
    {
        $service = $request->getService();
        $serviceOption = $request->getServiceOption();

        if (true === (bool) $request->enabled) {
            if (0 === $serviceOption->servicePrices()->count()) {
                throw new BadRequestHttpException('Can not be enabled with no prices');
            }
        }

        $serviceOption->update($request->only(['name', 'unit_id', 'min_order', 'order_multiples', 'enabled']));

        return new JsonResponse([
            'messaage' => 'Service option has been updated',
            'data' => (new ServiceOptionDetailTransformer($serviceOption))->toArray(),
        ]);
    }

    /**
     * delete serviceOption.
     *
     * @param DeleteServiceOptionRequest $request
     *
     * @return json
     */
    public function deleteOption(DeleteServiceOptionRequest $request)
    {
        $serviceOption = $request->getServiceOption();
        $serviceOption->delete();

        return new JsonResponse(['messaage' => 'Service option has been deleted']);
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
                'structure' => $configuration->getStructure(),
            ];
        }

        return new JsonResponse(['data' => $types]);
    }

    /**
     * calculate prise.
     *
     * @param CalculateServiceOptionPriceRequest $request
     *
     * @return json
     */
    public function calculatePrice(CalculateServiceOptionPriceRequest $request)
    {
        $serviceOption = $request->getServiceOption();

        $calculator = new ServicePriceCalculator($serviceOption);

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
                'configurations' => $calculation->getConfigurations(),
            ]);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * update sorting.
     *
     * @param UpdateSortingServiceOptionRequest $request
     *
     * @return json
     */
    public function updateSorting(UpdateSortingServiceOptionRequest $request)
    {
        foreach ($request->data as $sort) {
            ServiceOption::find($sort['id'])->update(['sort' => $sort['sort']]);
        }

        return new JsonResponse(['message' => 'Service has been sorted']);
    }
}
