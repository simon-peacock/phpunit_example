#!/usr/bin/env groovy

@Library('lightning-shared-libraries@master') _

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
               buildPhpSite()
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
