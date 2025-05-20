<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute 必須被接受。',
    'accepted_if' => '當 :other 為 :value 時，:attribute 必須被接受。',
    'active_url' => ':attribute 必須是一個有效的網址。',
    'after' => ':attribute 必須是 :date 之後的日期。',
    'after_or_equal' => ':attribute 必須是 :date 之後或相同的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、數字、破折號和底線。',
    'alpha_num' => ':attribute 只能包含字母和數字。',
    'any_of' => ':attribute 是無效的。',
    'array' => ':attribute 必須是一個陣列。',
    'ascii' => ':attribute 只能包含單一位元組的英數字元和符號。',
    'before' => ':attribute 必須是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必須是 :date 之前或相同的日期。',
    'between' => [
        'array' => ':attribute 必須有介於 :min 至 :max 個項目。',
        'file' => ':attribute 檔案大小必須介於 :min 至 :max KB 之間。',
        'numeric' => ':attribute 必須介於 :min 至 :max 之間。',
        'string' => ':attribute 必須介於 :min 至 :max 個字元之間。',
    ],
    'boolean' => ':attribute 必須是 true 或 false。',
    'can' => ':attribute 欄位包含未授權的值。',
    'confirmed' => ':attribute 確認欄位不符。',
    'contains' => ':attribute 欄位缺少必要的值。',
    'current_password' => '密碼不正確。',
    'date' => ':attribute 必須是有效的日期。',
    'date_equals' => ':attribute 必須是等於 :date 的日期。',
    'date_format' => ':attribute 必須符合 :format 的格式。',
    'decimal' => ':attribute 必須有 :decimal 位小數。',
    'declined' => ':attribute 必須被拒絕。',
    'declined_if' => '當 :other 為 :value 時，:attribute 必須被拒絕。',
    'different' => ':attribute 和 :other 必須不同。',
    'digits' => ':attribute 必須是 :digits 位數字。',
    'digits_between' => ':attribute 必須介於 :min 至 :max 位數字之間。',
    'dimensions' => ':attribute 圖片尺寸無效。',
    'distinct' => ':attribute 欄位有重複的值。',
    'doesnt_end_with' => ':attribute 欄位結尾不能是以下其中之一：:values。',
    'doesnt_start_with' => ':attribute 欄位開頭不能是以下其中之一：:values。',
    'email' => ':attribute 必須是有效的電子郵件地址。',
    'ends_with' => ':attribute 欄位結尾必須是以下其中之一：:values。',
    'enum' => '選擇的 :attribute 是無效的。',
    'exists' => '選擇的 :attribute 是無效的。',
    'extensions' => ':attribute 檔案必須是以下其中一種副檔名：:values。',
    'file' => ':attribute 必須是一個檔案。',
    'filled' => ':attribute 欄位必須有值。',
    'gt' => [
        'array' => ':attribute 必須有超過 :value 個項目。',
        'file' => ':attribute 檔案大小必須大於 :value KB。',
        'numeric' => ':attribute 必須大於 :value。',
        'string' => ':attribute 必須多於 :value 個字元。',
    ],
    'gte' => [
        'array' => ':attribute 必須有 :value 個或更多項目。',
        'file' => ':attribute 檔案大小必須大於或等於 :value KB。',
        'numeric' => ':attribute 必須大於或等於 :value。',
        'string' => ':attribute 必須多於或等於 :value 個字元。',
    ],
    'hex_color' => ':attribute 必須是有效的十六進制顏色碼。',
    'image' => ':attribute 必須是一張圖片。',
    'in' => '選擇的 :attribute 是無效的。',
    'in_array' => ':attribute 欄位必須存在於 :other 中。',
    'integer' => ':attribute 必須是一個整數。',
    'ip' => ':attribute 必須是有效的 IP 位址。',
    'ipv4' => ':attribute 必須是有效的 IPv4 位址。',
    'ipv6' => ':attribute 必須是有效的 IPv6 位址。',
    'json' => ':attribute 必須是有效的 JSON 字串。',
    'list' => ':attribute 必須是一個列表。',
    'lowercase' => ':attribute 必須是小寫。',
    'lt' => [
        'array' => ':attribute 必須有少於 :value 個項目。',
        'file' => ':attribute 檔案大小必須小於 :value KB。',
        'numeric' => ':attribute 必須小於 :value。',
        'string' => ':attribute 必須少於 :value 個字元。',
    ],
    'lte' => [
        'array' => ':attribute 必須有少於或等於 :value 個項目。',
        'file' => ':attribute 檔案大小必須小於或等於 :value KB。',
        'numeric' => ':attribute 必須小於或等於 :value。',
        'string' => ':attribute 必須少於或等於 :value 個字元。',
    ],
    'mac_address' => ':attribute 必須是有效的 MAC 位址。',
    'max' => [
        'array' => ':attribute 必須有少於或等於 :max 個項目。',
        'file' => ':attribute 檔案大小不得大於 :max KB。',
        'numeric' => ':attribute 不得大於 :max。',
        'string' => ':attribute 字元數不得大於 :max。',
    ],
    'max_digits' => ':attribute 不得有超過 :max 位數字。',
    'mimes' => ':attribute 檔案類型必須是：:values。',
    'mimetypes' => ':attribute 檔案類型必須是：:values。',
    'min' => [
        'array' => ':attribute 必須至少有 :min 個項目。',
        'file' => ':attribute 檔案大小至少為 :min KB。',
        'numeric' => ':attribute 至少為 :min。',
        'string' => ':attribute 至少為 :min 個字元。',
    ],
    'min_digits' => ':attribute 必須至少有 :min 位數字。',
    'missing' => ':attribute 欄位必須是缺少的。',
    'missing_if' => '當 :other 為 :value 時，:attribute 欄位必須是缺少的。',
    'missing_unless' => '除非 :other 為 :value，否則 :attribute 欄位必須是缺少的。',
    'missing_with' => '當 :values 存在時，:attribute 欄位必須是缺少的。',
    'missing_with_all' => '當 :values 都存在時，:attribute 欄位必須是缺少的。',
    'multiple_of' => ':attribute 必須是 :value 的倍數。',
    'not_in' => '選擇的 :attribute 是無效的。',
    'not_regex' => ':attribute 格式無效。',
    'numeric' => ':attribute 必須是數字。',
    'password' => [
        'letters' => ':attribute 欄位必須至少包含一個字母。',
        'mixed' => ':attribute 欄位必須至少包含一個大寫和一個小寫字母。',
        'numbers' => ':attribute 欄位必須至少包含一個數字。',
        'symbols' => ':attribute 欄位必須至少包含一個符號。',
        'uncompromised' => '提供的 :attribute 曾出現在資料外洩中。請選擇不同的 :attribute。',
    ],
    'present' => ':attribute 欄位必須存在。',
    'present_if' => '當 :other 為 :value 時，:attribute 欄位必須存在。',
    'present_unless' => '除非 :other 為 :value，否則 :attribute 欄位必須存在。',
    'present_with' => '當 :values 存在時，:attribute 欄位必須存在。',
    'present_with_all' => '當 :values 都存在時，:attribute 欄位必須存在。',
    'prohibited' => ':attribute 欄位是被禁止的。',
    'prohibited_if' => '當 :other 為 :value 時，:attribute 欄位是被禁止的。',
    'prohibited_if_accepted' => '當 :other 被接受時，:attribute 欄位是被禁止的。',
    'prohibited_if_declined' => '當 :other 被拒絕時，:attribute 欄位是被禁止的。',
    'prohibited_unless' => '除非 :other 在 :values 中，否則 :attribute 欄位是被禁止的。',
    'prohibits' => ':attribute 欄位禁止 :other 出現。',
    'regex' => ':attribute 格式無效。',
    'required' => ':attribute 欄位為必填。',
    'required_array_keys' => ':attribute 欄位必須包含以下鍵：:values。',
    'required_if' => '當 :other 為 :value 時，:attribute 欄位為必填。',
    'required_if_accepted' => '當 :other 被接受時，:attribute 欄位為必填。',
    'required_if_declined' => '當 :other 被拒絕時，:attribute 欄位為必填。',
    'required_unless' => '除非 :other 在 :values 中，否則 :attribute 欄位為必填。',
    'required_with' => '當 :values 存在時，:attribute 欄位為必填。',
    'required_with_all' => '當 :values 都存在時，:attribute 欄位為必填。',
    'required_without' => '當 :values 不存在時，:attribute 欄位為必填。',
    'required_without_all' => '當 :values 都不存在時，:attribute 欄位為必填。',
    'same' => ':attribute 必須與 :other 相符。',
    'size' => [
        'array' => ':attribute 必須包含 :size 個項目。',
        'file' => ':attribute 檔案大小必須為 :size KB。',
        'numeric' => ':attribute 必須為 :size。',
        'string' => ':attribute 必須為 :size 個字元。',
    ],
    'starts_with' => ':attribute 欄位開頭必須是以下其中之一：:values。',
    'string' => ':attribute 必須是一個字串。',
    'timezone' => ':attribute 必須是有效的時區。',
    'unique' => ':attribute 已被使用。',
    'uploaded' => ':attribute 上傳失敗。',
    'uppercase' => ':attribute 必須是大寫。',
    'url' => ':attribute 必須是有效的網址。',
    'ulid' => ':attribute 必須是有效的 ULID。',
    'uuid' => ':attribute 必須是有效的 UUID。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
