#!/usr/bin/env groovy

pipeline {
    agent { label 'drupal8' }
    triggers { githubPush() }
    options { disableConcurrentBuilds() }
    stages {
        stage('Unit Test') {
           when {
               expression { branch "PR-*" }
           }
           steps {
                sh '''
                    composer create-project drupal-composer/drupal-project:8.x-dev drupal --stability dev --no-interaction
                    mkdir -p drupal/web/modules/${JOB_NAME%/*} && rsync -av --progress . drupal/web/modules/${JOB_NAME%/*} --exclude drupal
                '''
           }
        }
        stage("PHPUnit") {
            sh 'vendor/phpunit/phpunit/phpunit --bootstrap build/bootstrap.php --configuration phpunit-coverage.xml'
        }

        stage("Publish Coverage") {
            publishHTML (target: [
                    allowMissing: false,
                    alwaysLinkToLastBuild: false,
                    keepAll: true,
                    reportDir: 'build/coverage',
                    reportFiles: 'index.html',
                    reportName: "Coverage Report"

            ])
        }

        stage("Publish Clover") {
            step([$class: 'CloverPublisher', cloverReportDir: 'build/logs', cloverReportFileName: 'clover.xml'])
        }

        stage('Checkstyle Report') {
            sh 'vendor/bin/phpcs --report=checkstyle --report-file=build/logs/checkstyle.xml --standard=phpcs.xml --extensions=php,inc --ignore=autoload.php --ignore=vendor/ app || exit 0'
            checkstyle pattern: 'build/logs/checkstyle.xml'
        }

        stage('Mess Detection Report') {
            sh 'vendor/bin/phpmd app xml phpmd.xml --reportfile build/logs/pmd.xml --exclude vendor/ --exclude autoload.php || exit 0'
            pmd canRunOnFailed: true, pattern: 'build/logs/pmd.xml'
        }

        stage('CPD Report') {
            sh 'phpcpd --log-pmd build/logs/pmd-cpd.xml --exclude vendor app || exit 0' /* should be vendor/bin/phpcpd but... conflicts... */
            dry canRunOnFailed: true, pattern: 'build/logs/pmd-cpd.xml'
        }

        stage('Lines of Code') {
            sh 'vendor/bin/phploc --count-tests --exclude vendor/ --log-csv build/logs/phploc.csv --log-xml build/logs/phploc.xml app'
        }
    }
}
