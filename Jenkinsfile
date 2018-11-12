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
                    mkdir -p drupal/web/modules/${JOB_NAME%/*} && rsync -av --progress . drupal/web/modules/${JOB_NAME%/*} --exclude drupal --quiet
                    mkdir coverage
                    drupal/vendor/bin/phpunit -c drupal/web/core drupal/web/modules/${JOB_NAME%/*}/tests/ --coverage-clover $WORKSPACE/reports/coverage.xml --log-junit $WORKSPACE/build/phpunit.xml
                '''
           }
        }

        stage("Publish Coverage") {
            steps {
                publishHTML (target: [
                    allowMissing: false,
                    alwaysLinkToLastBuild: false,
                    keepAll: true,
                    reportDir: 'build/coverage',
                    reportFiles: 'phpunit.xml',
                    reportName: "Coverage Report"

                ])
            }
        }

        stage("Publish Clover") {
            steps {
                step([$class: 'CloverPublisher', cloverReportDir: 'build/logs', cloverReportFileName: 'clover.xml'])
            }
        }

        stage('Checkstyle Report') {
            steps {
                sh 'vendor/bin/phpcs --report=checkstyle --report-file=build/logs/checkstyle.xml --standard=phpcs.xml --extensions=php,inc --ignore=autoload.php --ignore=vendor/ app || exit 0'
                checkstyle pattern: 'build/logs/checkstyle.xml'
            }
        }

        stage('Mess Detection Report') {
            steps {
                sh 'vendor/bin/phpmd app xml phpmd.xml --reportfile build/logs/pmd.xml --exclude vendor/ --exclude autoload.php || exit 0'
                pmd canRunOnFailed: true, pattern: 'build/logs/pmd.xml'
            }
        }

        stage('CPD Report') {
            steps {
                sh 'phpcpd --log-pmd build/logs/pmd-cpd.xml --exclude vendor app || exit 0' /* should be vendor/bin/phpcpd but... conflicts... */
                dry canRunOnFailed: true, pattern: 'build/logs/pmd-cpd.xml'
            }
        }

        stage('Lines of Code') {
            steps {
                sh 'vendor/bin/phploc --count-tests --exclude vendor/ --log-csv build/logs/phploc.csv --log-xml build/logs/phploc.xml app'
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
