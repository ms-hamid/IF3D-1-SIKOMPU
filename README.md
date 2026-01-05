# SIKOMPU: AI-Driven Lecturer Allocation System

![Project Status](https://img.shields.io/badge/Status-Completed-success?style=for-the-badge)
![Framework](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Python](https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white)
![ML](https://img.shields.io/badge/XGBoost-111111?style=for-the-badge&logo=xgboost&logoColor=white)
![License](https://img.shields.io/badge/License-Polibatam-blue?style=for-the-badge)

> **A Decision Support System (DSS) optimizing lecturer course assignments using Machine Learning (XGBoost) and Self-Assessment data.**

## 📖 About The Project

**SIKOMPU** (*Sistem Penentuan Koordinator dan Pengampu*) is a web-based application designed to solve the challenges of subjectivity and inefficiency in the academic lecturer assignment process. By integrating historical data and comprehensive competency metrics (Self-Assessment, Certificates, Education, and Research), the system provides objective, data-driven recommendations for course coordinators and lecturers.

This project was developed as a **Project-Based Learning (PBL)** initiative at the Department of Informatics Engineering, Politeknik Negeri Batam.

### Key Solutions
* **Objectivity:** Replaces manual, subjective meetings with data-driven AI recommendations.
* **Efficiency:** Automates the complex matching process between lecturer qualifications and course requirements.
* **Competency Tracking:** Centralizes lecturer competency data, including self-assessments and professional certifications.

## 🚀 Key Features

The system serves two primary user roles: **Admin (Department Structure)** and **Lecturers**.

* **🤖 AI-Driven Recommendation Engine:** Utilizes the **XGBoost** algorithm to predict lecturer-course suitability with a tested accuracy of **98.92%** 1.
* **📊 Interactive Dashboard:** Real-time visualization of lecturer statistics, course distribution, and assignment status.
* **📝 Self-Assessment Module:** An intuitive interface allowing lecturers to rate their own teaching confidence (scale 1-8) for specific courses.
* **📂 Competency Management:** Digital management of lecturer credentials, including certificate validation and research history .
* **⚖️ Business Constraint Logic:** Automated application of academic rules (e.g., Minimum S2 degree for theoretical courses, certificate validity period).
* **📑 Comprehensive Reporting:** Exportable assignment results and reports in PDF and Excel formats for audit purposes.

## 🛠️ Tech Stack

The project implements a **Service-Oriented Architecture** separating the web application from the AI computation service.

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Web Framework** | **Laravel** | Core web application, UI, and database management. |
| **AI Service** | **Flask (Python)** | REST API service for data preprocessing and ML inference . |
| **Machine Learning** | **XGBoost** | The core classification algorithm for predicting allocation success. |
| **Database** | **MySQL** | Relational database storage. |

## ⚙️ System Architecture

The system utilizes a RESTful API communication flow between the Laravel frontend and the Python/Flask backend:

1.  **Data Transmission:** Laravel aggregates lecturer profiles and sends them as JSON payloads to the Flask service.
2.  **Processing:** Flask handles data preprocessing (encoding & scaling) and feeds it into the trained `model.pkl`.
3.  **Prediction:** The XGBoost model calculates suitability probability scores.
4.  **Constraint Application:** Laravel receives the scores and applies hard academic constraints (filtering) before presenting the final recommendation to the user.

## 📄 License

Copyright © 2026 Politeknik Negeri Batam. All Rights Reserved.

---
*Developed for the Odd Semester PBL 2025-2026.*