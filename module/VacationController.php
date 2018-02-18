<?php

namespace module;

use library\controller\Controller;
use module\model\User;
use module\model\Vacation;
use module\model\VacationForm;

class VacationController extends Controller
{
    private const CRUD_CREATE = 'create';
    private const CRUD_UPDATE = 'update';
    private const CRUD_DELETE = 'delete';

    /**
     * @var User
     */
    private $user;
    /**
     * @var Vacation
     */
    private $vacation;

    public function init()
    {
        $this->vacation = new Vacation();
        $this->user = new User();
        parent::init();
    }

    public function run()
    {
        $path = $this->request->getPath();

        $data = null;

        switch ($path) {
            case static::CRUD_CREATE:
                $data = $this->actionCreate();
                break;

            case static::CRUD_UPDATE:
                $data = $this->actionUpdate($this->request->getQueryParam('id'));
                break;

            case static::CRUD_DELETE:
                $data = $this->actionDelete($this->request->getQueryParam('id'));
                break;

            default:
                $data = $this->actionIndex();
        }

        return $data;
    }

    public function actionCreate()
    {
        /* @var $model VacationForm */
        $model = new VacationForm();

        if ($model->load($this->request->getBodyParams()) && $model->validate()) {
            $model->add();
            $this->redirect('index');
        }

        return $this->render('form', [
            'model' => $model,
            'action' => 'create',
            'total' => $this->vacation->getAllDays(),
            'used' => $this->vacation->getUsedDaysInYear($this->user->getId()),
        ]);
    }

    public function actionUpdate(int $id)
    {
        $data = $this->getVacationModel($id);
        /* @var $model VacationForm */
        $model = new VacationForm(false);
        $model->days = $data->days;
        $model->status = $data->status;

        if ($model->load($this->request->getBodyParams()) && $model->validate()) {
            $model->update($id);
            $this->redirect('index');
        }

        return $this->render('form', [
            'model' => $model,
            'action' => 'update&id=' . $id,
            'total' => $this->vacation->getAllDays(),
            'used' => $this->vacation->getUsedDaysInYear($this->user->getId()),
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'model' => $this->vacation->getAllByUser($this->user->getId()),
            'total' => $this->vacation->getAllDays(),
            'used' => $this->vacation->getUsedDaysInYear($this->user->getId()),
        ]);
    }

    public function actionDelete(int $id)
    {
        $data = $this->getVacationModel($id);
        $data->delete();

        $this->redirect('index');
    }

    protected function getVacationModel(int $id): Vacation
    {
        if ($model = $this->vacation->getOneByUser($id, $this->user->getId())) {
            return $model;
        }

        throw new \Exception('Requested data does not exist');
    }
}