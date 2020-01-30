<?php
namespace verbb\wishlist\controllers;

use Craft;
use craft\web\Controller;

class BaseController extends Controller
{
    // Protected Methods
    // =========================================================================

    protected function returnSuccess($message, $params = [])
    {
        $request = Craft::$app->getRequest();

        if ($request->getAcceptsJson()) {
            $params['success'] = true;

            return $this->asJson($params);
        }

        if ($request->getIsCpRequest()) {
            Craft::$app->getSession()->setNotice(Craft::t('wishlist', $message));
        }

        if ($request->getIsPost()) {
            return $this->redirectToPostedUrl();
        }

        return $this->redirect($request->referrer);
    }

    protected function returnError($message, $params = [])
    {
        $request = Craft::$app->getRequest();

        $error = Craft::t('wishlist', $message);

        if ($request->getAcceptsJson()) {
            return $this->asJson(['error' => $error]);
        }

        if ($request->getIsCpRequest()) {
            Craft::$app->getSession()->setError($error);
        }

        if ($request->getIsPost()) {
            Craft::$app->getSession()->setError($error);

            if ($params) {
                Craft::$app->getUrlManager()->setRouteParams($params);
            }

            return null;
        }

        return $this->redirect($request->referrer);
    }
}
