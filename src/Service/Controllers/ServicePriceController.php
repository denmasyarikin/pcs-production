<?php

namespace Denmasyarikin\Production\Service\Controllers;

use Modules\Chanel\Chanel;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\Requests\DetailServiceOptionRequest;
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
     * @param DetailServiceOptionRequest $request
     *
     * @return json
     */
    public function getList(DetailServiceOptionRequest $request)
    {
        $serviceOption = $request->getServiceOption();

        return new JsonResponse([
            'data' => (new ServicePriceListTransformer($serviceOption->servicePrices()->orderBy('id', 'DESC')->get()))->toArray(),
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
        $serviceOption = $request->getServiceOption();
        $this->checkIsOptionPriceExist($serviceOption, $request->chanel_id);

        $data = $request->only(['chanel_id', 'price']);
        $data['current'] = true;

        $calculator = new ServicePriceCalculator($serviceOption);
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

        $price = $serviceOption->servicePrices()->create($data);

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
        $option = $request->getServiceOption();
        $price = $request->getServicePrice();

        $calculator = new ServicePriceCalculator($option);
        $defaultPrice = null;

        if (null === $price->chanel_id) {
            $defaultPrice = $calculator->getBasePrice();
            $option->servicePrices()
                    ->update(['current' => false]);
        } else {
            $defaultPrice = $calculator->getChanelPrice($price->chanel);
            $option->servicePrices()
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
     * check is service option price exist.
     *
     * @param ServiceOption $serviceOption
     * @param mixed         $chanelId
     */
    protected function checkIsOptionPriceExist(ServiceOption $serviceOption, $chanelId = null)
    {
        $servicePrices = $serviceOption->servicePrices();

        if (is_null($chanelId)) {
            $servicePrices->whereNull('chanel_id');
        } else {
            $servicePrices->whereChanelId($chanelId);
        }

        if ($servicePrices->whereCurrent(true)->count() > 0) {
            throw new BadRequestHttpException('Option price already exist');
        }

        return;
    }
}
