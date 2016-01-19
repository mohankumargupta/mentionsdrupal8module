#!/bin/bash
set -x
echo $DRUPAL_TI_WEBSERVER_URL
echo $DRUPAL_TI_WEBSERVER_PORT
echo $DRUPAL_TI_DRUPAL_DIR
behat -dl