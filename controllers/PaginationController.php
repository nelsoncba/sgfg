<?php

namespace app\controllers;

class PaginationController extends \yii\web\Controller
{
    public function actionIndex()
    {
        
	    $query = Tablereport::find();
	    $countQuery = clone $query;
	     
	    $pages = new Pagination(['totalCount' => $countQuery->count()]);
	     
	    $dataProvider = new ActiveDataProvider([
	        'query' => $query,
	    ]);
	     return $this->render('GridView', [
	         'dataProvider' => $dataProvider,
	         'pages' => $pages,
	    ]);

	    }

	}
