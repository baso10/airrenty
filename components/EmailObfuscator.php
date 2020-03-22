<?php

/*
  Copyright (c) 2014, Brian Pfretzschner
  All rights reserved.

  Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:

 * Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

 * Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
  AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
  IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
  FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
  DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
  CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
  OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
  OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace app\components;

use \yii\base\Widget;
use \yii\helpers\Html;
use \yii\validators\EmailValidator;

/**
 * Description of EmailObfuscator
 *
 */
class EmailObfuscator extends Widget {

  public $email;

  public function init() {
    parent::init();
  }

  public function run() {
    if (!$this->email || !(new EmailValidator())->validate($this->email)) {
      echo $this->email;
      return;
    }

    $email = Html::encode($this->email);
    $at_index = strpos($email, '@');
    $email = str_replace('@', '', $email);
    $rot_mail = str_rot13($email);

    echo '<script type="text/javascript">
var action=":otliam".split("").reverse().join("");
var href="' . $rot_mail . '".replace(/[a-zA-Z]/g, function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);});
href=href.substr(0, ' . $at_index . ') + String.fromCharCode(4*2*2*4) + href.substr(' . $at_index . ');
var a = "<a href=\""+action+href+"\">"+href+"</a>";
document.write(a);
</script>';
  }

}
