package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data

import com.google.gson.annotations.SerializedName

data class DashboardDto_V_1_0_1(
    @SerializedName("type")
    val type: String?,
    @SerializedName("message")
    val message: String?,
    @SerializedName("data")
    var data: DashboardDataDto_V_1_0_1? = DashboardDataDto_V_1_0_1(),
)

data class DashboardDataDto_V_1_0_1(
    @SerializedName("is_logged_in" )
    var isLoggedIn: Boolean? = null,
    @SerializedName("data")
    var data: ArrayList<DashboardDataItemDto_V_1_0_1> = arrayListOf(),
)

data class DashboardDataItemDto_V_1_0_1(
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
    var categories: ArrayList<DashboardCategoryDto_V_1_0_1> = arrayListOf(),
)

data class DashboardCategoryDto_V_1_0_1(
    @SerializedName("category_id")
    var categoryId: String? = null,
    @SerializedName("category_title")
    var categoryTitle: String? = null,
    @SerializedName("category_type")
    var categoryType: String? = null,
    @SerializedName("task_items")
    var taskItems: ArrayList<DashboardTaskItemDto_V_1_0_1> = arrayListOf(),
)

data class DashboardTaskItemDto_V_1_0_1(
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
    @SerializedName("quiz_attempt")
    var quizAttempt: QuizAttemptDto_V_1_0_1? = QuizAttemptDto_V_1_0_1(),
    @SerializedName("leaderboard")
    var leaderboard: ArrayList<String> = arrayListOf(),
)

data class QuizAttemptDto_V_1_0_1(
    @SerializedName("total_questions")
    val totalQuestions: Long? = 0L,
    @SerializedName("correct_answers")
    val correctAnswers: Long? = 0L,
    @SerializedName("wrong_answers")
    val wrongAnswers: Long? = 0L,
    @SerializedName("score")
    val score: Double? = 0.0,
)

/*
data class DashboardTaskCategoryDtoV1(
    @SerializedName("category_id")
    val categoryId: String,
    @SerializedName("category_title")
    val categoryTitle: String?,
    @SerializedName("category_type")
    val categoryType: String,
    @SerializedName("task_items")
    val taskItems: List<DashboardTaskItemDto> = listOf(),
)

data class DashboardTaskItemDtoV1(
    @SerializedName("item_id")
    val itemId: String,
    @SerializedName("item_title")
    val itemTitle: String?,
    @SerializedName("item_subtitle")
    val itemSubtitle: String?,
    @SerializedName("item_button_label")
    val itemButtonLabel: String?,
    @SerializedName("access_mode")
    val accessMode: String,
    @SerializedName("item_type")
    val itemType: String,
)

data class DashboardDtoV1(
    @SerializedName("response_type")
    val responseType: String,
    @SerializedName("is_logged_in")
    val isLoggedIn: Boolean,
    @SerializedName("task_category")
    val taskCategory: List<DashboardTaskCategoryDtoV1> = listOf(),
)*/
