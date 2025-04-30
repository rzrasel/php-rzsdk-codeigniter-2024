package com.rzrasel.wordquiz.presentation.screens.flatpractice

import android.app.Application
import android.content.Context
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll
import androidx.compose.runtime.Composable
import androidx.compose.runtime.MutableState
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableIntStateOf
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.unit.dp
import androidx.lifecycle.viewmodel.compose.viewModel
import com.rzrasel.wordquiz.presentation.components.components.AppTopAppBar
import com.rzrasel.wordquiz.presentation.components.dialog.LoadingDialog
import com.rzrasel.wordquiz.presentation.components.layout.DefaultScaffold
import com.rzrasel.wordquiz.presentation.screens.data.UserTaskItemModel
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.FlatPracticeModule
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.FlatPracticeViewModel
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeAnswerSetSubmitted
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeModel
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeQuestionSetSubmitted
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeRequest
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.state.FlatPracticeUiEvent
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.state.FlatPracticeUiState
import com.rzrasel.wordquiz.presentation.screens.flatpractice.layout.FlatPracticeButtonView
import com.rzrasel.wordquiz.presentation.screens.flatpractice.layout.FlatPracticeQuestionView
import com.rzrasel.wordquiz.ui.theme.AppTheme

@Composable
fun AppFlatPracticeScreen_V_1_0_1(
    application: Application,
    userTaskItem: UserTaskItemModel = UserTaskItemModel(userAuthToken = "", categoryId = "", itemId = ""),
    onClickBackButton: ()-> Unit,
) {
    val viewModel = remember {
        FlatPracticeModule.provideFlatPracticeViewModel(application.applicationContext, userTaskItem.userAuthToken)
    }
    //val uiState by remember { viewModel.uiState }
    val uiState by viewModel.uiState.collectAsState()
    var showLoadingDialog by remember { mutableStateOf(false) }
    //
    when(uiState) {
        is FlatPracticeUiState.Idle -> {
            viewModel.getFlatPracticeInitial(
                userAuthToken = userTaskItem.userAuthToken,
                categoryId = userTaskItem.categoryId,
                itemId = userTaskItem.itemId,
            )
        } is FlatPracticeUiState.Success -> {
            showLoadingDialog = false
            val flatPracticeModel: FlatPracticeModel = (uiState as FlatPracticeUiState.Success).flatPracticeModel
        FlatPracticeScreen_V_1_0_1(
                application = application,
                viewModel = viewModel,
                userTaskItem = userTaskItem,
                flatPracticeModel = flatPracticeModel,
                onClickBackButton = onClickBackButton,
            )
        } else -> {
            showLoadingDialog = when(uiState) {
                is FlatPracticeUiState.Loading -> {
                    true
                }
                else -> {
                    false
                }
            }
        }
    }
    //
    /*FlatPracticeScreen(
        application = application,
        viewModel = viewModel,
        userTaskItem = userTaskItem,
        onClickBackButton = onClickBackButton,
    )*/

    if(showLoadingDialog) {
        LoadingDialog("Please wait...") {
            showLoadingDialog = !showLoadingDialog
        }
    }
}

@Composable
fun FlatPracticeScreen_V_1_0_1(
    application: Application,
    viewModel: FlatPracticeViewModel = viewModel(),
    flatPracticeModel: FlatPracticeModel,
    userTaskItem: UserTaskItemModel = UserTaskItemModel(userAuthToken = "", categoryId = "", itemId = ""),
    onClickBackButton: ()-> Unit,
) {
    val context: Context = LocalContext.current
    val remQuestionIndex: MutableState<Int> = remember { mutableIntStateOf(0) }
    val mcqQuestionState by viewModel.mcqQuestionState.collectAsState()
    //
    DefaultScaffold(
        topBar = {
            AppTopAppBar(
                title = "Practice and Memorize",
                onClick = {
                    onClickBackButton()
                }
            )
        }
    ) {
        Box(
            modifier = Modifier
                .fillMaxSize()
                .padding(top = 64.dp)
        ) {
            Column(
                modifier = Modifier
                    .fillMaxSize()
                    .verticalScroll(rememberScrollState())
                    .padding(horizontal = AppTheme.dimens.paddingLarge)
                    .padding(bottom = AppTheme.dimens.paddingExtraLarge)
            ) {
                /*flatPracticeModel.questionSet.forEachIndexed { index, question ->
                    remQuestionIndex.value = flatPracticeModel.questionSet.size
                    FlatPracticeQuestionView(index, question, viewModel)
                    Spacer(Modifier.height(16.dp))
                }*/
                /*mcqQuestionState.questionSet.forEachIndexed { index, question ->
                    remQuestionIndex.value = flatPracticeModel.questionSet.size
                    FlatPracticeQuestionView(index, question, viewModel)
                    Spacer(Modifier.height(16.dp))
                }*/
                /*FlatPracticeQuestionView(remQuestionIndex.value, mcqQuestionState.questionSet[remQuestionIndex.value], viewModel)*/
                /*//_V_1_0_1
                FlatPracticeQuestionView(remQuestionIndex.value, mcqQuestionState.data.questionSet[remQuestionIndex.value], viewModel)
                Spacer(Modifier.height(16.dp))
                FlatPracticeButtonView(
                    currentIndex = remQuestionIndex,
                    totalQuestion = flatPracticeModel.questionSet.size,
                    isSingleQuestionView = true,
                    onClickSubmit = { index ->
                        //Toast.makeText(context, "Coming soon $index - ${flatPracticeModel.questionSet.size}", Toast.LENGTH_LONG).show()
                        val questionSet: ArrayList<FlatPracticeQuestionSetSubmitted> = arrayListOf()
                        flatPracticeModel.questionSet.forEach { question ->
                            val answerSet: ArrayList<FlatPracticeAnswerSetSubmitted> = arrayListOf()
                            question.answerSet.forEach { item ->
                                answerSet.add(
                                    FlatPracticeAnswerSetSubmitted(
                                        answerId = item.answerId,
                                        answer = item.answer,
                                        isTure = item.isSelected,
                                    )
                                )
                            }
                            questionSet.add(
                                FlatPracticeQuestionSetSubmitted(
                                    questionId = question.questionId,
                                    question = question.question,
                                    answerSet = answerSet,
                                )
                            )
                        }
                        val flatPracticeRequest: FlatPracticeRequest = FlatPracticeRequest(
                            userAuthToken = userTaskItem.userAuthToken,
                            categoryId = userTaskItem.categoryId,
                            itemId = userTaskItem.itemId,
                            questionSet = questionSet,
                        )
                        //Toast.makeText(context, "Coming soon - ${flatReadingModel.questionSet[index].question}", Toast.LENGTH_LONG).show()
                        viewModel.onUiEvent(FlatPracticeUiEvent.Submit(flatPracticeRequest = flatPracticeRequest))
                    }
                )
                //_V_1_0_1*/
                /*Row(
                    modifier = Modifier
                        .fillMaxWidth(),
                    horizontalArrangement = Arrangement.SpaceBetween
                ) {
                    ButtonComposable(
                        backgroundColor = Color("#de7b73".toColorInt()),
                        contentColor = Color("#ffffff".toColorInt()),
                        fontWeight = FontWeight.Bold,
                        cornerRadius = 6.dp,
                        text = "Previous",
                        onClick = {
                            val previousIndex = remQuestionIndex.value - 1
                            if(previousIndex < 0) {
                                Toast.makeText(context, "Go for previous", Toast.LENGTH_LONG).show()
                            } else {
                                remQuestionIndex.value = previousIndex
                            }
                        }
                    )
                    ButtonComposable(
                        backgroundColor = Color("#239cbc".toColorInt()),
                        contentColor = Color("#e9fcff".toColorInt()),
                        fontWeight = FontWeight.Bold,
                        cornerRadius = 6.dp,
                        text = "Next",
                        onClick = {
                            val nextIndex = remQuestionIndex.value + 1
                            if(nextIndex >= flatPracticeModel.questionSet.size) {
                                Toast.makeText(context, "Go for next", Toast.LENGTH_LONG).show()
                            } else {
                                remQuestionIndex.value = nextIndex
                            }
                            //Toast.makeText(context, "Coming soon $nextIndex - ${flatReadingModel.questionSet.size}", Toast.LENGTH_LONG).show()
                        }
                    )
                }*/
            }
        }
    }
}