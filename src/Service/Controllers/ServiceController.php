<?php

namespace Denmasyarikin\Production\Service\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Production\Service\Service;
use Denmasyarikin\Production\Service\Requests\CreateServiceRequest;
use Denmasyarikin\Production\Service\Requests\DetailServiceRequest;
use Denmasyarikin\Production\Service\Requests\UpdateServiceRequest;
use Denmasyarikin\Production\Service\Requests\DeleteServiceRequest;
use Denmasyarikin\Production\Service\Transformers\ServiceListTransformer;
use Denmasyarikin\Production\Service\Transformers\ServiceDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ServiceController extends Controller
{
    /**
     * service list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $services = $this->getServiceList($request, $request->get('status'));
        $draftServices = $this->getServiceList($request, 'draft');

        $transform = new ServiceListTransformer($services);
        $transformDraft = new ServiceListTransformer($draftServices);

        return new JsonResponse([
            'data' => $transform->toArray(),
            'draft' => $transformDraft->toArray(),
        ]);
    }

    /**
     * get service list.
     *
     * @param Request $request
     * @param string  $status
     *
     * @return paginator
     */
    protected function getServiceList(Request $request, $status = null)
    {
        $services = Service::orderBy('id', 'ASC');

        if ($request->has('key')) {
            $services->where('name', 'like', "%{$request->key}%");
        }

        switch ($status) {
            case 'all':
                // do nothing
                break;

            case 'draft':
                $services->whereStatus('draft');
                break;

            case 'inactive':
                $services->whereStatus('inactive');
                break;

            default:
                $services->whereStatus('active');
                break;
        }

        return $services->paginate($request->get('per_page') ?: 10);
    }

    /**
     * get detail.
     *
     * @param DetailProductRequest $request
     *
     * @return json
     */
    public function getDetail(DetailServiceRequest $request)
    {
        $transform = new ServiceDetailTransformer($request->getService());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create service.
     *
     * @param CreateServiceRequest $request
     *
     * @return json
     */
    public function createService(CreateServiceRequest $request)
    {
        $service = Service::create($request->only([
            'name', 'description',
        ]));

        return new JsonResponse([
            'message' => 'Service has been created',
            'data' => (new ServiceDetailTransformer($service))->toArray(),
        ], 201);
    }

    /**
     * update service.
     *
     * @param UpdateServiceRequest $request
     *
     * @return json
     */
    public function updateService(UpdateServiceRequest $request)
    {
        $service = $request->getService();

        if ('draft' !== $request->status
            and 0 === $service->serviceTypes()->count()) {
            throw new BadRequestHttpException('Can not update status with No Types');
        }

        $service->update($request->only([
            'name', 'description', 'status',
        ]));

        return new JsonResponse([
            'message' => 'Service has been updated',
            'data' => (new ServiceDetailTransformer($service))->toArray(),
        ]);
    }

    /**
     * update service.
     *
     * @param DeleteServiceRequest $request
     *
     * @return json
     */
    public function deleteService(DetailServiceRequest $request)
    {
        $service = $request->getService();

        $service->delete();

        return new JsonResponse(['message' => 'Service has been deleted']);
    }
}
