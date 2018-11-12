#!/usr/bin/env groovy

pipeline {
    agent { label 'dennis-php' }
    triggers { githubPush() }
    options { disableConcurrentBuilds() }
    stages {


        stage('Unit Test') {
           when {
               expression { branch "PR-*" }
           }
           steps {
                sh '''
                    composer install \
                    && vendor/bin/phpunit -c tests/
                '''
           }
        }


        stage('Static Code Analysis') {
           when {
               expression { branch "PR-*" }
           }
           steps {
               withSonarQubeEnv('lighting-prototype') {
                   sh "sonar-scanner "
               }
           }
        }
    }
}
