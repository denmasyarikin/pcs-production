<?php

namespace Denmasyarikin\Production\Service\Controllers;

use Modules\Chanel\Chanel;
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
use Denmasyarikin\Production\Service\Factories\ServicePriceCalculator;

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

        $data = $request->only(['chanel_id', 'price']);
        $data['current'] = true;

        $calculator = new ServicePriceCalculator($serviceType);
        $defaultPrice = null;

        if (null !== $request->chanel_id) {
            $chanel = Chanel::whereStatus('active')->find(intval($request->chanel_id));
            if (!is_null($chanel)) {
                $defaultPrice = $calculator->getChanelPrice($chanel);
            }
        }

        if ($defaultPrice) {
            $data['previous_id'] = $defaultPrice->id;
            $data['change_type'] = $request->price > $defaultPrice->price ? 'up' : 'down';
            $data['difference'] = $request->price - $defaultPrice->price;
        }

        $price = $serviceType->servicePrices()->create($data);

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
        $type = $request->getServiceType();
        $price = $request->getServicePrice();

        $calculator = new ServicePriceCalculator($type);
        $defaultPrice = null;

        if (null === $price->chanel_id) {
            $defaultPrice = $calculator->getBasePrice();
            $type->servicePrices()
                    ->update(['current' => false]);
        } else {
            $defaultPrice = $calculator->getChanelPrice($price->chanel);
            $type->servicePrices()
                    ->whereChanelId($price->chanel_id)
                    ->update(['current' => false]);
        }

        $newPrice = $price->replicate();
        $newPrice->price = $request->price;
        $newPrice->current = true;
        $newPrice->previous_id = $newPrice->id;
        $newPrice->change_type = $newPrice->price > $defaultPrice->price ? 'up' : 'down';
        $newPrice->difference = $newPrice->price - $defaultPrice->price;
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
     * check is service type price exist.
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
