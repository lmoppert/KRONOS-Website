<?php 

class BBUtilities {

    static public function checkToken() {
        $token = JUtility::getToken();
        $flag = false;
        $jinput = JFactory::getApplication()->input;
        if($jinput->get->getInt($token, false, 'INT') === 1) $flag = true;
        if($jinput->post->getInt($token, false, 'INT') === 1) $flag = true;
        if(!$flag) throw new Exception(JText::_('EXCEPTION_CROSS_REQUEST'));
        return $flag;
    }

    static public function generateRandomHash($base = null, $max = 9999) {
        return md5($base . mt_rand(0, $max));
    }

    static public function getPercentage($num, $of) {
        return ($num != 0) ? (round(($num * 100) / $of) . '%') : ('0%');
    }

}