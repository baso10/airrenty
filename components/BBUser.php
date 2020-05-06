<?php

/*
 * Copyright 2020 baso10.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace app\components;

use Yii;
use yii\web\User;
use yii\db\Exception as DBException;

/**
 *
 * @author baso10
 */
class BBUser extends User {

  private $model = false;

  public function getModel() {
    if ($this->model === false) {
      $this->model = \app\models\User::findIdentity($this->id);
      if ($this->model === null) {
        throw new ForbiddenHttpException();
      }
    }
    return $this->model;
  }

  public function isSuperAdmin() {
    if ($this->getIsGuest()) {
      return false;
    }
    $model = $this->getModel();
    if ($model != null) {
      return $model->is_super_admin == 1;
    }

    return false;
  }

  /**
   * Returns a value indicating whether the user is a guest (not authenticated).
   * @return boolean whether the current user is a guest.
   * @see getIdentity()
   */
  public function getIsGuest() {
    try {
      return $this->getIdentity() === null;
    } catch (DBException $e) {
      Yii::error($e);
      return true;
    }
  }
  
}
