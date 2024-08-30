pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building...'
                // Example for Maven
                sh 'mvn clean package'
            }
        }
        stage('Unit and Integration Tests') {
            steps {
                echo 'Running Unit and Integration Tests...'
                // Example for Maven
                sh 'mvn test'
            }
            post {
                success {
                    script {
                        def log = currentBuild.rawBuild.getLog(100).join("\n")
                        mail to: 'ypokia07@gmail.com',
                            subject: "Unit and Integration Tests Successful",
                            body: "The unit and integration tests completed successfully.\n\nHere are the last 100 lines of the log:\n${log}"
                    }
                }
                failure {
                    script {
                        def log = currentBuild.rawBuild.getLog(100).join("\n")
                        mail to: 'ypokia07@gmail.com',
                            subject: "Unit and Integration Tests Failed",
                            body: "The unit and integration tests failed. Please check the logs.\n\nHere are the last 100 lines of the log:\n${log}"
                    }
                }
            }
        }
        stage('Code Analysis') {
            steps {
                echo 'Performing Code Analysis...'
                // Example for SonarQube
                sh 'sonar-scanner'
            }
        }
        stage('Security Scan') {
            steps {
                echo 'Running Security Scan...'
                // Example for OWASP Dependency-Check
                sh './dependency-check.sh'
            }
            post {
                success {
                    script {
                        def log = currentBuild.rawBuild.getLog(100).join("\n")
                        mail to: 'ypokia07@gmail.com',
                            subject: "Security Scan Successful",
                            body: "The security scan completed successfully.\n\nHere are the last 100 lines of the log:\n${log}"
                    }
                }
                failure {
                    script {
                        def log = currentBuild.rawBuild.getLog(100).join("\n")
                        mail to: 'ypokia07@gmail.com',
                            subject: "Security Scan Failed",
                            body: "The security scan failed. Please check the logs.\n\nHere are the last 100 lines of the log:\n${log}"
                    }
                }
            }
        }
        stage('Deploy to Staging') {
            steps {
                echo 'Deploying to Staging...'
                // Example deployment command
                sh 'aws deploy'
            }
        }
        stage('Integration Tests on Staging') {
            steps {
                echo 'Running Integration Tests on Staging...'
                // Example for Selenium
                sh 'mvn verify'
            }
        }
        stage('Deploy to Production') {
            steps {
                echo 'Deploying to Production...'
                // Example deployment command
                sh 'aws deploy'
            }
        }
    }
    
    post {
        always {
            echo 'Pipeline completed.'
        }
        success {
            script {
                def log = currentBuild.rawBuild.getLog(100).join("\n")
                mail to: 'ypokia07@gmail.com',
                    subject: "Pipeline Successful: ${currentBuild.fullDisplayName}",
                    body: "The pipeline has completed successfully.\n\nHere are the last 100 lines of the log:\n${log}"
            }
        }
        failure {
            script {
                def log = currentBuild.rawBuild.getLog(100).join("\n")
                mail to: 'ypokia07@gmail.com',
                    subject: "Pipeline Failed: ${currentBuild.fullDisplayName}",
                    body: "The pipeline has failed. Please check the logs.\n\nHere are the last 100 lines of the log:\n${log}"
            }
        }
    }
}
