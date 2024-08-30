pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Task: Build the code using a build automation tool (e.g., Maven).'
                echo 'Tool: Maven'
            }
        }
        stage('Unit and Integration Tests') {
            steps {
                echo 'Task: Run unit and integration tests.'
                echo 'Tool: JUnit for unit tests, TestNG for integration tests'
            }
            post {
                success {
                    script {
                        def logFile = "unit_integration_tests_log.txt"
                        writeFile file: logFile, text: currentBuild.rawBuild.getLog(100).join("\n")
                        archiveArtifacts artifacts: logFile, allowEmptyArchive: true
                        mail to: 'ypokia07@gmail.com',
                            subject: "Unit and Integration Tests Successful",
                            body: "The unit and integration tests completed successfully. Please find the logs attached.",
                            attachmentsPattern: logFile
                    }
                }
                failure {
                    script {
                        def logFile = "unit_integration_tests_log.txt"
                        writeFile file: logFile, text: currentBuild.rawBuild.getLog(100).join("\n")
                        archiveArtifacts artifacts: logFile, allowEmptyArchive: true
                        mail to: 'ypokia07@gmail.com',
                            subject: "Unit and Integration Tests Failed",
                            body: "The unit and integration tests failed. Please find the logs attached.",
                            attachmentsPattern: logFile
                    }
                }
            }
        }
        stage('Code Analysis') {
            steps {
                echo 'Task: Perform code analysis to ensure code meets industry standards.'
                echo 'Tool: SonarQube'
            }
        }
        stage('Security Scan') {
            steps {
                echo 'Task: Perform a security scan to identify vulnerabilities.'
                echo 'Tool: OWASP Dependency-Check'
            }
            post {
                success {
                    script {
                        def logFile = "security_scan_log.txt"
                        writeFile file: logFile, text: currentBuild.rawBuild.getLog(100).join("\n")
                        archiveArtifacts artifacts: logFile, allowEmptyArchive: true
                        mail to: 'ypokia07@gmail.com',
                            subject: "Security Scan Successful",
                            body: "The security scan completed successfully. Please find the logs attached.",
                            attachmentsPattern: logFile
                    }
                }
                failure {
                    script {
                        def logFile = "security_scan_log.txt"
                        writeFile file: logFile, text: currentBuild.rawBuild.getLog(100).join("\n")
                        archiveArtifacts artifacts: logFile, allowEmptyArchive: true
                        mail to: 'ypokia07@gmail.com',
                            subject: "Security Scan Failed",
                            body: "The security scan failed. Please find the logs attached.",
                            attachmentsPattern: logFile
                    }
                }
            }
        }
        stage('Deploy to Staging') {
            steps {
                echo 'Task: Deploy the application to a staging server.'
                echo 'Tool: AWS CLI'
            }
        }
        stage('Integration Tests on Staging') {
            steps {
                echo 'Task: Run integration tests on the staging environment.'
                echo 'Tool: Selenium'
            }
        }
        stage('Deploy to Production') {
            steps {
                echo 'Task: Deploy the application to the production server.'
                echo 'Tool: AWS CLI'
            }
        }
    }
    
    post {
        always {
            echo 'Pipeline completed.'
        }
        success {
            script {
                echo "Pipeline Successful: ${currentBuild.fullDisplayName}"
            }
        }
        failure {
            script {
                echo "Pipeline Failed: ${currentBuild.fullDisplayName}"
            }
        }
    }
}
