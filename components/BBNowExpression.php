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

use yii\db\Expression;

class BBNowExpression extends Expression {

  /**
   * Constructor.
   * @param string $expression the DB expression
   * @param array $params parameters
   * @param array $config name-value pairs that will be used to initialize the object properties
   */
  public function __construct($params = [], $config = []) {
    parent::__construct("NOW() AT TIME ZONE 'utc'", $params, $config);
  }

}
