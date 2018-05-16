<?php

namespace Denmasyarikin\Production\Service\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\Requests\DetailServiceTypeRequest;
use Denmasyarikin\Production\Service\Requests\CreateServicePriceRequest;
use Denmasyarikin\Production\Service\Requests\UpdateServicePriceRequest;
use Denmasyarikin\Production\Service\Requests\DeleteServicePriceRequest;
use Denmasyarikin\Production\Service\Transformers\ServicePriceListTransformer;
use Denmasyarikin\Production\Service\Transformers\ServicePriceDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ServicePriceController extends Controller
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
            'data' => (new ServicePriceListTransformer($serviceType->servicePrices()->orderBy('id', 'DESC')->get()))->toArray(),
        ]);
    }

    /**
     * create price.
     *
     * @param CreateServicePriceRequest $request
     *
     * @return json
     */
    public function createPrice(CreateServicePriceRequest $request)
    {
        $serviceType = $request->getServiceType();
        $this->checkIsTypePriceExist($serviceType, $request->chanel_id);

        $price = $serviceType->servicePrices()->create(
            $request->only(['chanel_id', 'price'])
        );

        return new JsonResponse([
            'messaage' => 'Service price has been created',
            'data' => (new ServicePriceDetailTransformer($price))->toArray(),
        ], 201);
    }

    /**
     * update price.
     *
     * @param UpdateServicePriceRequest $request
     *
     * @return json
     */
    public function updatePrice(UpdateServicePriceRequest $request)
    {
        $variant = $request->getServiceType();
        $price = $request->getServicePrice();

        if (null === $price->chanel_id) {
            $variant->servicePrices()
                    ->update(['current' => false]);
        } else {
            $variant->servicePrices()
                    ->whereChanelId($price->chanel_id)
                    ->update(['current' => false]);
        }

        $newPrice = $price->replicate();
        $newPrice->price = $request->price;
        $newPrice->current = true;
        $newPrice->save();

        return new JsonResponse([
            'messaage' => 'Service price has been updated',
            'data' => (new ServicePriceDetailTransformer($newPrice))->toArray(),
        ]);
    }

    /**
     * delete price.
     *
     * @param DeleteServicePriceRequest $request
     *
     * @return json
     */
    public function deletePrice(DeleteServicePriceRequest $request)
    {
        $price = $request->getServicePrice();

        if ((bool) $price->current) {
            throw new BadRequestHttpException('Current Price not allowed to delete');
        }

        $price->delete();

        return new JsonResponse(['messaage' => 'Service price has been deleted']);
    }

    /**
     * check is service variant price exist.
     *
     * @param param type $serviceType
     * @param mixed      $chanelId
     */
    protected function checkIsTypePriceExist(ServiceType $serviceType, $chanelId = null)
    {
        $servicePrices = $serviceType->servicePrices();

        if (is_null($chanelId)) {
            $servicePrices->whereNull('chanel_id');
        } else {
            $servicePrices->whereChanelId($chanelId);
        }

        if ($servicePrices->whereCurrent(true)->count() > 0) {
            throw new BadRequestHttpException('Type price already exist');
        }

        return;
    }
}
