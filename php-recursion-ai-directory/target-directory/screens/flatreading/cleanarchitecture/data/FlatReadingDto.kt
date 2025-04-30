package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class FlatReadingDto(
    @SerializedName("type")
    val type: String?,
    @SerializedName("message")
    val message: String?,
    @SerializedName("data")
    var data: FlatReadingDataDto? = FlatReadingDataDto(),
)

data class FlatReadingDataDto(
    @SerializedName("next_question_index" )
    var nextQuestionIndex: Long? = 0L,
    @SerializedName("is_last_question" )
    var isLastQuestion: Boolean? = null,
    @SerializedName("data")
    var data: FlatReadingQuestionDataDto? = FlatReadingQuestionDataDto(),
)

data class FlatReadingQuestionDataDto(
    @SerializedName("question_id")
    val questionId: String? = null,
    @SerializedName("question")
    val question: String? = null,
    @SerializedName("answer_set")
    val answerSet: List<FlatReadingAnswerSetDto> = listOf(),
)

data class FlatReadingAnswerSetDto(
    @SerializedName("answer_id")
    val answerId: String? = null,
    @SerializedName("answer")
    val answer: String? = null,
    @SerializedName("is_ture")
    val isTure: Boolean = false,
)

/*data class FlatReadingDto(
    @SerializedName("response_type")
    val responseType: String,
    @SerializedName("is_logged_in")
    val isLoggedIn: Boolean,
    @SerializedName("question_set")
    val questionSet: ArrayList<FlatReadingQuestionSetDto> = arrayListOf(),
)

data class FlatReadingQuestionSetDto(
    @SerializedName("question_id")
    val questionId: String,
    @SerializedName("question")
    val question: String,
    @SerializedName("answer_set")
    val answerSet: ArrayList<FlatReadingAnswerSetDto> = arrayListOf(),
)

data class FlatReadingAnswerSetDto(
    @SerializedName("answer_id")
    val answerId: String,
    @SerializedName("answer")
    val answer: String,
    @SerializedName("is_ture")
    val isTure: Boolean,
)*/
