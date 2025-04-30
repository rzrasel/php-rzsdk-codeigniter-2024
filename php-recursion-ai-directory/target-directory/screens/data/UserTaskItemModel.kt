package com.rzrasel.wordquiz.presentation.screens.data

import com.google.gson.annotations.SerializedName
import kotlinx.serialization.Serializable

@Serializable
data class UserTaskItemModel(
    var userAuthToken: String = "",
    @SerializedName("category_id")
    var categoryId: String,
    @SerializedName("category_type")
    val categoryType: String = "empty",
    @SerializedName("item_id")
    var itemId: String,
    @SerializedName("current_question_index")
    var currentQuestionIndex: Int = 0,
    @SerializedName("access_mode")
    val accessMode: String = "private",
)