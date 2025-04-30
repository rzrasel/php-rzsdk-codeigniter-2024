package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class FlatPracticeDto(
    @SerializedName("type")
    val type: String = "error",
    @SerializedName("message")
    val message: String?,
    @SerializedName("data")
    var data: FlatPracticeDataDto? = FlatPracticeDataDto(),
)

data class FlatPracticeDataDto(
    @SerializedName("next_question_index")
    var nextQuestionIndex: Long? = 0L,
    @SerializedName("is_last_question")
    var isLastQuestion: Boolean? = null,
    @SerializedName("data")
    var questionSet: ArrayList<FlatPracticeQuestionItemDto>? = arrayListOf(),
)

data class FlatPracticeQuestionItemDto(
    @SerializedName("question_id")
    val questionId: String? = null,
    @SerializedName("question")
    val question: String? = null,
    @SerializedName("answer_set")
    val answerSet: List<FlatPracticeAnswerItemDto> = listOf(),
)

data class FlatPracticeAnswerItemDto(
    @SerializedName("answer_id")
    val answerId: String? = null,
    @SerializedName("answer")
    val answer: String? = null,
    @SerializedName("is_ture")
    val isTure: Boolean = false,
)