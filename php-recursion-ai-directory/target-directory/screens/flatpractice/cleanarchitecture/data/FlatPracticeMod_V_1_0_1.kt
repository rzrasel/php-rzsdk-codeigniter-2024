package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class FlatPracticeModel_V_1_0_1(
    @SerializedName("type")
    val type: String? = null,
    @SerializedName("message")
    val message: String? = null,
    @SerializedName("data")
    var data: FlatPracticeDataModel_V_1_0_1? = FlatPracticeDataModel_V_1_0_1(),
)

data class FlatPracticeDataModel_V_1_0_1(
    @SerializedName("next_question_index" )
    var nextQuestionIndex: Long? = 0L,
    @SerializedName("is_last_question" )
    var isLastQuestion: Boolean? = null,
    @SerializedName("data")
    var data: FlatPracticeQuestionDataModel_V_1_0_1? = FlatPracticeQuestionDataModel_V_1_0_1(),
)

data class FlatPracticeQuestionDataModel_V_1_0_1(
    @SerializedName("question_id")
    val questionId: String? = null,
    @SerializedName("question")
    val question: String? = null,
    @SerializedName("answer_set")
    val answerSet: List<FlatPracticeAnswerSetModel_V_1_0_1> = listOf(),
)

data class FlatPracticeAnswerSetModel_V_1_0_1(
    @SerializedName("answer_id")
    val answerId: String? = null,
    @SerializedName("answer")
    val answer: String? = null,
    @SerializedName("is_ture")
    val isTure: Boolean = false,
)

/*
class FlatPracticeModel(
    @SerializedName("response_type")
    val responseType: String,
    @SerializedName("is_logged_in")
    val isLoggedIn: Boolean,
    //@SerializedName("question_set")
    val questionSet: ArrayList<FlatPracticeQuestionSetModel> = arrayListOf(),
)

data class FlatPracticeQuestionSetModel(
    @SerializedName("question_id")
    val questionId: String,
    @SerializedName("question")
    val question: String,
    var answerSet: ArrayList<FlatPracticeAnswerSetModel> = arrayListOf(),
)

data class FlatPracticeAnswerSetModel(
    @SerializedName("answer_id")
    val answerId: String,
    @SerializedName("answer")
    val answer: String,
    @SerializedName("is_ture")
    val isTure: Boolean,
    var isSelected: Boolean = false,
)*/
