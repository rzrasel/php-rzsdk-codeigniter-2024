package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class FlatPracticeRequest(
    @SerializedName("user_auth_token")
    val userAuthToken: String? = "",
    @SerializedName("category_id")
    val categoryId: String,
    @SerializedName("item_id")
    val itemId: String,
    @SerializedName("question_set")
    val questionSet: ArrayList<FlatPracticeQuestionSetSubmitted> = arrayListOf(),
)

data class FlatPracticeQuestionSetSubmitted(
    @SerializedName("question_id")
    val questionId: String,
    @SerializedName("question")
    val question: String,
    var answerSet: ArrayList<FlatPracticeAnswerSetSubmitted> = arrayListOf(),
)

data class FlatPracticeAnswerSetSubmitted(
    @SerializedName("answer_id")
    val answerId: String,
    @SerializedName("answer")
    val answer: String,
    @SerializedName("is_ture")
    val isTure: Boolean = false,
)