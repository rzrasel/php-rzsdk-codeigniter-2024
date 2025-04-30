package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class FlatReadingRequest(
    @SerializedName("user_auth_token")
    val userAuthToken: String?,
    @SerializedName("category_id")
    val categoryId: String,
    @SerializedName("item_id")
    val itemId: String,
    @SerializedName("question_id")
    val questionId: String = "",
    @SerializedName("current_question_index")
    val currentQuestionIndex: Int = 0,
)