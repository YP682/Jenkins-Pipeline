pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building...'
                // Use 'bat' for Windows commands
                bat 'mvn clean package'
            }
        }
        stage('Unit and Integration Tests') {
            steps {
                echo 'Running Unit and Integration Tests...'
                bat 'mvn test'
            }
            post {
                success {
                    script {
                        def log = currentBuild.getLog(100).join("\n")
                        mail to: 'ypokia07@gmail.com',
                            subject: "Unit and Integration Tests Successful",
                            body: "The unit and integration tests completed successfully.\n\nHere are the last 100 lines of the log:\n${log}"
                    }
                }
                failure {
                    script {
                        def log = currentBuild.getLog(100).join("\n")
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
                bat 'sonar-scanner'
            }
        }
        stage('Security Scan') {
            steps {
                echo 'Running Security Scan...'
                bat './dependency-check.sh'
            }
            post {
                success {
                    script {
                        def log = currentBuild.getLog(100).join("\n")
                        mail to: 'ypokia07@gmail.com',
                            subject: "Security Scan Successful",
                            body: "The security scan completed successfully.\n\nHere are the last 100 lines of the log:\n${log}"
                    }
                }
                failure {
                    script {
                        def log = currentBuild.getLog(100).join("\n")
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
                bat 'aws deploy'
            }
        }
        stage('Integration Tests on Staging') {
            steps {
                echo 'Running Integration Tests on Staging...'
                bat 'mvn verify'
            }
        }
        stage('Deploy to Production') {
            steps {
                echo 'Deploying to Production...'
                bat 'aws deploy'
            }
        }
    }
    
    post {
        always {
            echo 'Pipeline completed.'
        }
        success {
            script {
                def log = currentBuild.getLog(100).join("\n")
                mail to: 'ypokia07@gmail.com',
                    subject: "Pipeline Successful: ${currentBuild.fullDisplayName}",
                    body: "The pipeline has completed successfully.\n\nHere are the last 100 lines of the log:\n${log}"
            }
        }
        failure {
            script {
                def log = currentBuild.getLog(100).join("\n")
                mail to: 'ypokia07@gmail.com',
                    subject: "Pipeline Failed: ${currentBuild.fullDisplayName}",
                    body: "The pipeline has failed. Please check the logs.\n\nHere are the last 100 lines of the log:\n${log}"
            }
        }
    }
}
