#!/usr/bin/env groovy

@Library('lightning-shared-libraries@master') _

pipeline {
    agent { label 'dennis-php' }
    triggers { githubPush() }
    options { disableConcurrentBuilds() }

    stages {

        stage('Build Environment') {
        
           when {
               expression { branch "PR-*" }
           }
           steps {
               standardInstallationDrupal()
           }
        }

        stage('Unit Test') {

           when {
               expression { branch "PR-*" }
           }
           steps {
               drupalPhpUnitTest()
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
