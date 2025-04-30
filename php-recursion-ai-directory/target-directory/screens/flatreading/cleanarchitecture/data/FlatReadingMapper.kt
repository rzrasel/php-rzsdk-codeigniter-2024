package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data

//Mapper (DTO → Model)

fun FlatReadingDto.toModel(): FlatReadingModel {
    return FlatReadingModel(
        type = type,
        message = message,
        data = data?.toDataModel(),
    )
}

fun FlatReadingDataDto.toDataModel(): FlatReadingDataModel {
    return FlatReadingDataModel(
        nextQuestionIndex = nextQuestionIndex,
        isLastQuestion = isLastQuestion,
        data = data?.toQuestionModel(),
    )
}

fun FlatReadingQuestionDataDto.toQuestionModel(): FlatReadingQuestionDataModel {
    return FlatReadingQuestionDataModel(
        questionId = questionId,
        question = question,
        answerSet = answerSet.map { it.toAnswerSetModel() },
    )
}

fun FlatReadingAnswerSetDto.toAnswerSetModel(): FlatReadingAnswerSetModel {
    return FlatReadingAnswerSetModel(
        answerId = answerId,
        answer = answer,
        isTure = isTure,
    )
}

//Mapper (Model → DTO)

fun FlatReadingModel.toDto(): FlatReadingDto {
    return FlatReadingDto(
        type = type,
        message = message,
        data = data?.toDataDto(),
    )
}

fun FlatReadingDataModel.toDataDto(): FlatReadingDataDto {
    return FlatReadingDataDto(
        nextQuestionIndex = nextQuestionIndex,
        isLastQuestion = isLastQuestion,
        data = data?.toQuestionDto(),
    )
}

fun FlatReadingQuestionDataModel.toQuestionDto(): FlatReadingQuestionDataDto {
    return FlatReadingQuestionDataDto(
        questionId = questionId,
        question = question,
        answerSet = answerSet.map { it.toAnswerSetDto() },
    )
}

fun FlatReadingAnswerSetModel.toAnswerSetDto(): FlatReadingAnswerSetDto {
    return FlatReadingAnswerSetDto(
        answerId = answerId,
        answer = answer,
        isTure = isTure,
    )
}