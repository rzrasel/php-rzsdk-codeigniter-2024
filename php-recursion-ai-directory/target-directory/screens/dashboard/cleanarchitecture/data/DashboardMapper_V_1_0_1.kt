package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data

//Mapper (DTO → Model)

fun DashboardDto_V_1_0_1.toDashboardModel(): DashboardModel_V_1_0_1 {
    return DashboardModel_V_1_0_1(
        type = type,
        message = message,
        data = data?.toDataModel()
    )
}

fun DashboardDataDto_V_1_0_1.toDataModel(): DashboardDataModel_V_1_0_1 {
    return DashboardDataModel_V_1_0_1(
        isLoggedIn = isLoggedIn,
        data = data.map { it.toDataListModel() } as ArrayList<DashboardDataItemModel_V_1_0_1>
    )
}

fun DashboardDataItemDto_V_1_0_1.toDataListModel(): DashboardDataItemModel_V_1_0_1 {
    return DashboardDataItemModel_V_1_0_1(
        quizId = quizId,
        quizTitle = quizTitle,
        quizDescription = quizDescription,
        totalQuestions = totalQuestions,
        createdAt = createdAt,
        updatedAt = updatedAt,
        categories = categories.map { it.toCategoryModel() } as ArrayList<DashboardCategoryModel_V_1_0_1>
    )
}

fun DashboardCategoryDto_V_1_0_1.toCategoryModel(): DashboardCategoryModel_V_1_0_1 {
    return DashboardCategoryModel_V_1_0_1(
        categoryId = categoryId,
        categoryTitle = categoryTitle,
        categoryType = categoryType,
        taskItems = taskItems.map { it.toTaskItemModel() } as ArrayList<DashboardTaskItemModel_V_1_0_1>
    )
}

fun DashboardTaskItemDto_V_1_0_1.toTaskItemModel(): DashboardTaskItemModel_V_1_0_1 {
    return DashboardTaskItemModel_V_1_0_1(
        itemId = itemId,
        itemTitle = itemTitle,
        itemSubtitle = itemSubtitle,
        itemButtonLabel = itemButtonLabel,
        accessMode = accessMode,
        itemType = itemType,
        quizAttempt = quizAttempt?.toQuizAttemptModel(),
        leaderboard = ArrayList(leaderboard)
    )
}

fun QuizAttemptDto_V_1_0_1.toQuizAttemptModel(): QuizAttemptModel_V_1_0_1 {
    return QuizAttemptModel_V_1_0_1(
        totalQuestions = totalQuestions,
        correctAnswers = correctAnswers,
        wrongAnswers = wrongAnswers,
        score = score
    )
}

//Mapper (Model → DTO)

fun DashboardModel_V_1_0_1.toDashboardDto(): DashboardDto_V_1_0_1 {
    return DashboardDto_V_1_0_1(
        type = type,
        message = message,
        data = data?.toDataDto()
    )
}

fun DashboardDataModel_V_1_0_1.toDataDto(): DashboardDataDto_V_1_0_1 {
    return DashboardDataDto_V_1_0_1(
        isLoggedIn = isLoggedIn,
        data = data.map { it.toDataListDto() } as ArrayList<DashboardDataItemDto_V_1_0_1>
    )
}

fun DashboardDataItemModel_V_1_0_1.toDataListDto(): DashboardDataItemDto_V_1_0_1 {
    return DashboardDataItemDto_V_1_0_1(
        quizId = quizId,
        quizTitle = quizTitle,
        quizDescription = quizDescription,
        totalQuestions = totalQuestions,
        createdAt = createdAt,
        updatedAt = updatedAt,
        categories = categories.map { it.toCategoryDto() } as ArrayList<DashboardCategoryDto_V_1_0_1>
    )
}

fun DashboardCategoryModel_V_1_0_1.toCategoryDto(): DashboardCategoryDto_V_1_0_1 {
    return DashboardCategoryDto_V_1_0_1(
        categoryId = categoryId,
        categoryTitle = categoryTitle,
        categoryType = categoryType,
        taskItems = taskItems.map { it.toTaskItemDto() } as ArrayList<DashboardTaskItemDto_V_1_0_1>
    )
}

fun DashboardTaskItemModel_V_1_0_1.toTaskItemDto(): DashboardTaskItemDto_V_1_0_1 {
    return DashboardTaskItemDto_V_1_0_1(
        itemId = itemId,
        itemTitle = itemTitle,
        itemSubtitle = itemSubtitle,
        itemButtonLabel = itemButtonLabel,
        accessMode = accessMode,
        itemType = itemType,
        quizAttempt = quizAttempt?.toQuizAttemptDto(),
        leaderboard = ArrayList(leaderboard)
    )
}

fun QuizAttemptModel_V_1_0_1.toQuizAttemptDto(): QuizAttemptDto_V_1_0_1 {
    return QuizAttemptDto_V_1_0_1(
        totalQuestions = totalQuestions,
        correctAnswers = correctAnswers,
        wrongAnswers = wrongAnswers,
        score = score
    )
}