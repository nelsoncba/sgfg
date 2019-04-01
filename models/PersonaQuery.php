<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Persona]].
 *
 * @see Persona
 */
class PersonaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Persona[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Persona|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getContacto()
}
