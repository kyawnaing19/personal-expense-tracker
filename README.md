 # 💰 Shwe Shaung — Personal & Group Expense Tracker API



A production-ready REST API built with **Laravel 11** for tracking personal and group expenses. Features Google OAuth authentication, budget management with push notifications, recurring transactions, and a full group expense splitting system.



---



## 🚀 Features



### Personal Expense Tracking

- Google OAuth 2.0 Authentication (via Sanctum)

- Transaction management with receipt image upload

- Custom & default expense/income categories

- Monthly budget management with alert thresholds

- Recurring transactions (daily/weekly/monthly) with Laravel Scheduler

- Pending/Confirmed/Rejected transaction workflow

- Firebase FCM push notifications (budget alerts, recurring reminders)



### Group Expense Tracking

- Create groups with admin/member roles

- Join via time-limited 6-digit code or admin direct invite

- Equally or custom expense splitting

- Partial payment settlement with payer confirmation flow

- Group balance overview (receivable/payable per member)

- Settlement history & audit trail

- FCM notifications for new expenses, settlements, and member joins



### Reports & Analytics

- Summary by date range (today/yesterday/this month/last month/custom)

- Category breakdown with percentages (income & expense)

- Budget vs actual spending overview



---



## 🛠️ Tech Stack



| Layer | Technology |

|---|---|

| Framework | Laravel 11 |

| Language | PHP 8.2 |

| Database | MySQL 8.0 |

| Authentication | Laravel Sanctum + Google OAuth 2.0 |

| Push Notification | Firebase Admin SDK (kreait/laravel-firebase) |

| File Storage | Laravel Storage (local/public) |

| Architecture | Repository → Service → Controller pattern |

| Primary Keys | ULID |

| Task Scheduling | Laravel Scheduler |



---

## 📦 Installation



### Requirements

- PHP >= 8.2

- Composer

- MySQL 8.0

- Firebase Project (for push notifications)


## 🔑 Authentication Flow



Flutter App

│

▼

Google Sign-In → ID Token

│

▼

POST /api/v1/auth/google

│

▼

Laravel verifies token → Creates/finds user → Returns Sanctum token

│

▼

Flutter stores token → Uses as Bearer token for all requests



---

## 🔔 Push Notification Types



| Type | Trigger | Recipients |

|---|---|---|

| budget_warning | Spending reaches alert threshold | Transaction creator |

| budget_exceeded | Spending exceeds budget | Transaction creator |

| group_expense_created | New group expense logged | Affected split members |

| settlement_claim | Payment claimed | Expense payer |

| settlement_confirmed | Payment confirmed | Claimant |

| settlement_rejected | Payment rejected | Claimant |

| member_invited | Admin adds member | New member + existing members |

| member_joined | Member joins via code | New member + existing members |



---



## 👤 Author



**Kyaw Kyaw Naing**

Internship Project — Za Information Technology Co., Ltd.

University of Computer Studies, Thaton (UCSTT)



---



## 📄 License



MIT License
