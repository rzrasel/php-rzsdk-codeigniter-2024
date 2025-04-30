package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data

//Mapper (DTO → Model) (DTO To Model)

fun FlatPracticeDto_V_1_0_1.toModel(): FlatPracticeModel_V_1_0_1 {
    return FlatPracticeModel_V_1_0_1(
        type = type,
        message = message,
        data = data?.toDataModel(),
    )
}

fun FlatPracticeDataDto_V_1_0_1.toDataModel(): FlatPracticeDataModel_V_1_0_1 {
    return FlatPracticeDataModel_V_1_0_1(
        nextQuestionIndex = nextQuestionIndex,
        isLastQuestion = isLastQuestion,
        data = data?.toQuestionModel(),
    )
}

fun FlatPracticeQuestionDataDto_V_1_0_1.toQuestionModel(): FlatPracticeQuestionDataModel_V_1_0_1 {
    return FlatPracticeQuestionDataModel_V_1_0_1(
        questionId = questionId,
        question = question,
        answerSet = answerSet.map { it.toAnswerSetModel() },
    )
}

fun FlatPracticeAnswerSetDto_V_1_0_1.toAnswerSetModel(): FlatPracticeAnswerSetModel_V_1_0_1 {
    return FlatPracticeAnswerSetModel_V_1_0_1(
        answerId = answerId,
        answer = answer,
        isTure = isTure,
    )
}

//Mapper (Model → DTO) (Model To DTO)

fun FlatPracticeModel_V_1_0_1.toDto(): FlatPracticeDto_V_1_0_1 {
    return FlatPracticeDto_V_1_0_1(
        type = type,
        message = message,
        data = data?.toDataDto(),
    )
}

fun FlatPracticeDataModel_V_1_0_1.toDataDto(): FlatPracticeDataDto_V_1_0_1 {
    return FlatPracticeDataDto_V_1_0_1(
        nextQuestionIndex = nextQuestionIndex,
        isLastQuestion = isLastQuestion,
        data = data?.toQuestionDto(),
    )
}

fun FlatPracticeQuestionDataModel_V_1_0_1.toQuestionDto(): FlatPracticeQuestionDataDto_V_1_0_1 {
    return FlatPracticeQuestionDataDto_V_1_0_1(
        questionId = questionId,
        question = question,
        answerSet = answerSet.map { it.toAnswerSetDto() },
    )
}

fun FlatPracticeAnswerSetModel_V_1_0_1.toAnswerSetDto(): FlatPracticeAnswerSetDto_V_1_0_1 {
    return FlatPracticeAnswerSetDto_V_1_0_1(
        answerId = answerId,
        answer = answer,
        isTure = isTure,
    )
}
