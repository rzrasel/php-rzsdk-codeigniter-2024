package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class FlatPracticeModel(
    @SerializedName("type")
    val type: String = "error",
    @SerializedName("message")
    val message: String? = null,
    @SerializedName("data")
    var data: FlatPracticeDataModel? = FlatPracticeDataModel(),
)

data class FlatPracticeDataModel(
    @SerializedName("next_question_index")
    var nextQuestionIndex: Long? = 0L,
    @SerializedName("is_last_question")
    var isLastQuestion: Boolean? = null,
    @SerializedName("data")
    var questionSet: ArrayList<FlatPracticeQuestionItemModel>? = arrayListOf(),
)

data class FlatPracticeQuestionItemModel(
    @SerializedName("question_id")
    val questionId: String? = null,
    @SerializedName("question")
    val question: String? = null,
    @SerializedName("answer_set")
    var answerSet: List<FlatPracticeAnswerItemModel> = listOf(),
)

data class FlatPracticeAnswerItemModel(
    @SerializedName("answer_id")
    val answerId: String? = null,
    @SerializedName("answer")
    val answer: String? = null,
    @SerializedName("is_ture")
    val isTure: Boolean = false,
    var isSelected: Boolean = false,
)