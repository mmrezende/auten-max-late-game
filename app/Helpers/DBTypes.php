<?php

namespace App\Helpers;

class DBTypes {
    const PAYMENT_PERIODS = ['monthly','biannual','yearly'];

    const NOTIFICATION_TYPES = ['financial', 'administrative', 'tournament'];

    const PAYMENT_METHODS = ['bolbradesco', 'credit_card'];

    const PAYMENT_STATUSES = ['pending', 'approved', 'authorized', 'in_process', 'in_mediation', 'rejected', 'cancelled', 'refunded', 'charged_back'];
}