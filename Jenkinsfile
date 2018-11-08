#!/usr/bin/env groovy

pipeline {
    agent { label 'drupal8' }
//    triggers {
//        bitbucketPush()
//    }
    options { disableConcurrentBuilds() }
    stages {
        stage('Static code analysis') {
           steps {
                sh '''
                    composer create-project drupal-composer/drupal-project:8.x-dev
                '''
            }

        }
    }
}

