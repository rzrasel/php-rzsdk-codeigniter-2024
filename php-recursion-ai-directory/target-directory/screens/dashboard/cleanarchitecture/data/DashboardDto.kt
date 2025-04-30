package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class DashboardDto(
    @SerializedName("type")
    val type: String?,
    @SerializedName("message")
    val message: String?,
    @SerializedName("data")
    var data: DashboardDataDto? = DashboardDataDto(),
)

data class DashboardDataDto(
    @SerializedName("access_token" )
    var accessToken: String? = null,
    @SerializedName("quizzes")
    var data: ArrayList<DashboardQuizItemDto> = arrayListOf(),
)

data class DashboardQuizItemDto(
    @SerializedName("quiz_id")
    var quizId: String? = null,
    @SerializedName("quiz_title")
    var quizTitle: String? = null,
    @SerializedName("quiz_description")
    var quizDescription: String? = null,
    @SerializedName("total_questions")
    var totalQuestions: Int? = null,
    @SerializedName("created_at")
    var createdAt: String? = null,
    @SerializedName("updated_at")
    var updatedAt: String? = null,
    @SerializedName("categories")
    var categories: ArrayList<DashboardCategoryDto> = arrayListOf(),
)

data class DashboardCategoryDto(
    @SerializedName("category_id")
    var categoryId: String? = null,
    @SerializedName("category_title")
    var categoryTitle: String? = null,
    @SerializedName("category_type")
    var categoryType: String? = null,
    @SerializedName("access_mode")
    var accessMode: String? = null,
    @SerializedName("task_items")
    var taskItems: ArrayList<DashboardTaskItemDto> = arrayListOf(),
)

data class DashboardTaskItemDto(
    @SerializedName("item_id")
    var itemId: String? = null,
    @SerializedName("item_title")
    var itemTitle: String? = null,
    @SerializedName("item_subtitle")
    var itemSubtitle: String? = null,
    @SerializedName("item_button_label")
    var itemButtonLabel: String? = null,
    @SerializedName("access_mode")
    var accessMode: String? = null,
    @SerializedName("item_type")
    var itemType: String? = null,
    /*@SerializedName("quiz_attempt")
    var quizAttempt: QuizAttemptDto? = QuizAttemptDto(),
    @SerializedName("leaderboard")
    var leaderboard: ArrayList<String> = arrayListOf(),*/
)

data class QuizAttemptDto(
    @SerializedName("total_questions")
    val totalQuestions: Long? = 0L,
    @SerializedName("correct_answers")
    val correctAnswers: Long? = 0L,
    @SerializedName("wrong_answers")
    val wrongAnswers: Long? = 0L,
    @SerializedName("score")
    val score: Double? = 0.0,
)