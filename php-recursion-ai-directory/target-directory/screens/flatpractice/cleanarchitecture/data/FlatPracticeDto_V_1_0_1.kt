package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class FlatPracticeDto_V_1_0_1(
    @SerializedName("type")
    val type: String?,
    @SerializedName("message")
    val message: String?,
    @SerializedName("data")
    var data: FlatPracticeDataDto_V_1_0_1? = FlatPracticeDataDto_V_1_0_1(),
)

data class FlatPracticeDataDto_V_1_0_1(
    @SerializedName("next_question_index" )
    var nextQuestionIndex: Long? = 0L,
    @SerializedName("is_last_question" )
    var isLastQuestion: Boolean? = null,
    @SerializedName("data")
    var data: FlatPracticeQuestionDataDto_V_1_0_1? = FlatPracticeQuestionDataDto_V_1_0_1(),
)

data class FlatPracticeQuestionDataDto_V_1_0_1(
    @SerializedName("question_id")
    val questionId: String? = null,
    @SerializedName("question")
    val question: String? = null,
    @SerializedName("answer_set")
    val answerSet: List<FlatPracticeAnswerSetDto_V_1_0_1> = listOf(),
)

data class FlatPracticeAnswerSetDto_V_1_0_1(
    @SerializedName("answer_id")
    val answerId: String? = null,
    @SerializedName("answer")
    val answer: String? = null,
    @SerializedName("is_ture")
    val isTure: Boolean = false,
)

/*
class FlatPracticeDto(
    @SerializedName("response_type")
    val responseType: String,
    @SerializedName("is_logged_in")
    val isLoggedIn: Boolean,
    @SerializedName("question_set")
    val questionSet: ArrayList<FlatPracticeQuestionSetDto> = arrayListOf(),
)

data class FlatPracticeQuestionSetDto(
    @SerializedName("question_id")
    val questionId: String,
    @SerializedName("question")
    val question: String,
    @SerializedName("answer_set")
    val answerSet: ArrayList<FlatPracticeAnswerSetDto> = arrayListOf(),
)

data class FlatPracticeAnswerSetDto(
    @SerializedName("answer_id")
    val answerId: String,
    @SerializedName("answer")
    val answer: String,
    @SerializedName("is_ture")
    val isTure: Boolean,
)*/
