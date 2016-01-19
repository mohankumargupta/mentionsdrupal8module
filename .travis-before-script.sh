#!/bin/bash
set -x
pwd
echo $DRUPAL_TI_WEBSERVER_URL
echo $DRUPAL_TI_WEBSERVER_PORT
echo $DRUPAL_TI_DRUPAL_DIR
echo $DRUPAL_TI_BEHAT_DIR
$DRUPAL_TI_BEHAT_DIR/vendor/bin/behat -dl --config $DRUPAL_TI_BEHAT_DIR/behat.yml

 