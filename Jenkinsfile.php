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
                script {
                    // Capture the log output to a file
                    bat 'echo Unit and Integration Tests Log > unit_integration_tests_log.txt'
                }
                // Ensure the file is created
                script {
                    bat 'dir /B'
                }
            }
            post {
                success {
                    script {
                        echo 'Preparing to send success email with console log attachment for Unit and Integration Tests...'
                        // Email with Jenkins console log attached
                        emailext (
                            to: 'ypokia07@gmail.com',
                            subject: "Unit and Integration Tests Successful",
                            body: "The unit and integration tests completed successfully. The console log is attached.",
                            attachLog: true // Attach Jenkins build logs (console output)
                        )
                        echo 'Success email with console log attachment sent for Unit and Integration Tests.'
                    }
                }
                failure {
                    script {
                        echo 'Preparing to send failure email with console log attachment for Unit and Integration Tests...'
                        // Email with Jenkins console log attached
                        emailext (
                            to: 'ypokia07@gmail.com',
                            subject: "Unit and Integration Tests Failed",
                            body: "The unit and integration tests failed. The console log is attached.",
                            attachLog: true // Attach Jenkins build logs (console output)
                        )
                        echo 'Failure email with console log attachment sent for Unit and Integration Tests.'
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
                script {
                    // Capture the log output to a file
                    bat 'echo Security Scan Log > security_scan_log.txt'
                }
                // Ensure the file is created
                script {
                    bat 'dir /B'
                }
            }
            post {
                success {
                    script {
                        echo 'Preparing to send success email with console log attachment for Security Scan...'
                        // Email with Jenkins console log attached
                        emailext (
                            to: 'ypokia07@gmail.com',
                            subject: "Security Scan Successful",
                            body: "The security scan completed successfully. The console log is attached.",
                            attachLog: true // Attach Jenkins build logs (console output)
                        )
                        echo 'Success email with console log attachment sent for Security Scan.'
                    }
                }
                failure {
                    script {
                        echo 'Preparing to send failure email with console log attachment for Security Scan...'
                        // Email with Jenkins console log attached
                        emailext (
                            to: 'ypokia07@gmail.com',
                            subject: "Security Scan Failed",
                            body: "The security scan failed. The console log is attached.",
                            attachLog: true // Attach Jenkins build logs (console output)
                        )
                        echo 'Failure email with console log attachment sent for Security Scan.'
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
