#!/bin/bash
set -x
echo $DRUPAL_TI_WEBSERVER_URL
echo $DRUPAL_TI_WEBSERVER_PORT
echo $DRUPAL_TI_DRUPAL_DIR
echo $DRUPAL_TI_BEHAT_DIR
$DRUPAL_TI_BEHAT_DIR/vendor/bin/behat -dl
find /home/travis -name behat.yml
 