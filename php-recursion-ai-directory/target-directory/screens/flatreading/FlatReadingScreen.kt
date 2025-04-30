package com.rzrasel.wordquiz.presentation.screens.flatreading

import android.app.Application
import android.content.Context
import android.widget.Toast
import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.MutableState
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableIntStateOf
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.painter.Painter
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.res.vectorResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.core.graphics.toColorInt
import androidx.lifecycle.viewmodel.compose.viewModel
import com.rzrasel.wordquiz.R
import com.rzrasel.wordquiz.presentation.components.components.AppTopAppBar
import com.rzrasel.wordquiz.presentation.components.components.ButtonComposable
import com.rzrasel.wordquiz.presentation.components.components.RoundedCornerChip
import com.rzrasel.wordquiz.presentation.components.dialog.LoadingDialog
import com.rzrasel.wordquiz.presentation.components.layout.DefaultScaffold
import com.rzrasel.wordquiz.presentation.screens.data.UserTaskItemModel
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.FlatReadingModule
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.FlatReadingViewModel
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingModel
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingRequest
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.state.FlatReadingUiEvent
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.state.FlatReadingUiState
import com.rzrasel.wordquiz.presentation.screens.flatreading.layout.FlatReadingButtonView
import com.rzrasel.wordquiz.presentation.screens.flatreading.layout.FlatReadingQuestionView
import com.rzrasel.wordquiz.ui.theme.AppTheme

@Composable
fun AppFlatReadingScreen(
    application: Application,
    userTaskItem: UserTaskItemModel = UserTaskItemModel(userAuthToken = "", categoryId = "", itemId = "", currentQuestionIndex = 0),
    onClickBackButton: ()-> Unit,
) {
    val viewModel = remember {
        FlatReadingModule.provideFlatReadingViewModel(application.applicationContext, userTaskItem.userAuthToken)
    }
    //val uiState by remember { viewModel.uiState }
    val uiState by viewModel.uiState.collectAsState()
    var showLoadingDialog by remember { mutableStateOf(false) }
    //val flatReadingModel: FlatReadingModel = uiState.Success.flatReadingModel
    //val flatReadingModel: FlatReadingModel = FlatReadingUiState.Success
    //
    /*viewModel.getFlatReadingInitial(
        userAuthToken = userTaskItem.userAuthToken,
        categoryId = userTaskItem.categoryId,
        itemId = userTaskItem.itemId,
    )*/
    //|------------------|DELETE THIS PORTION|-------------------|
    userTaskItem.categoryId = "1"
    userTaskItem.itemId = "1"
    userTaskItem.currentQuestionIndex = 9
    /*Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(top = 20.dp)
    ) {
        Text(text = "App Flat Reading Screen")
    }*/
    when(uiState) {
        is FlatReadingUiState.Idle -> {
            viewModel.getFlatReadingInitial(
                userAuthToken = userTaskItem.userAuthToken,
                categoryId = userTaskItem.categoryId,
                itemId = userTaskItem.itemId,
            )
        }
        is FlatReadingUiState.Success -> {
            showLoadingDialog = false
            val flatReadingModel: FlatReadingModel = (uiState as FlatReadingUiState.Success).flatReadingModel
            FlatReadingScreen(
                application = application,
                viewModel = viewModel,
                flatReadingModel = flatReadingModel,
                userTaskItem = userTaskItem,
                onClickBackButton = onClickBackButton,
            )
        }
        else -> {
            //viewModel.getDashboard(userAuthToken = userModel.userAuthToken)
            showLoadingDialog = when(uiState) {
                is FlatReadingUiState.Loading -> {
                    true
                }
                else -> {
                    false
                }
            }
        }
    }
    /*FlatReadingScreen(
        application = application,
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
fun FlatReadingScreen(
    application: Application,
    viewModel: FlatReadingViewModel = viewModel(),
    flatReadingModel: FlatReadingModel,
    userTaskItem: UserTaskItemModel = UserTaskItemModel(userAuthToken = "", categoryId = "", itemId = ""),
    onClickBackButton: ()-> Unit,
) {
    val context: Context = LocalContext.current
    val remQuestionIndex: MutableState<Int> = remember { mutableIntStateOf(0) }
    DefaultScaffold(
        topBar = {
            AppTopAppBar(
                title = "Read and Memorize",
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
                    //.weight(weight = 1f, fill = false)
                    //.weight(weight = 1f, fill = false)
                    .padding(horizontal = AppTheme.dimens.paddingLarge)
                    .padding(bottom = AppTheme.dimens.paddingExtraLarge)
            ) {
                /*flatReadingModel.questionSet.forEachIndexed { index, question ->
                    remQuestionIndex.value = flatReadingModel.questionSet.size
                    FlatReadingQuestionView(index, question)
                    Spacer(Modifier.height(16.dp))
                }*/
                FlatReadingQuestionView(
                    remQuestionIndex.value,
                    flatReadingModel.data?.data!!
                    //flatReadingModel.questionSet[remQuestionIndex.value]
                )
                Spacer(Modifier.height(16.dp))
                FlatReadingButtonView(
                    currentIndex = remQuestionIndex,
                    totalQuestion = 1,
                    isSingleQuestionView = true,
                    onClickSubmit = { index ->
                        //Toast.makeText(context, "Coming soon $index - ${flatReadingModel.questionSet.size}", Toast.LENGTH_LONG).show()
                        val flatReadingRequest: FlatReadingRequest = FlatReadingRequest(
                            userAuthToken = userTaskItem.userAuthToken,
                            categoryId = userTaskItem.categoryId,
                            itemId = userTaskItem.itemId,
                            questionId = flatReadingModel.data?.data?.questionId!!,
                        )
                        //Toast.makeText(context, "Coming soon - ${flatReadingModel.questionSet[index].question}", Toast.LENGTH_LONG).show()
                        viewModel.onUiEvent(FlatReadingUiEvent.Submit(flatReadingRequest = flatReadingRequest))
                    }
                )
                /*FlatReadingButtonView(
                    currentIndex = remQuestionIndex,
                    totalQuestion = flatReadingModel.questionSet.size,
                    isSingleQuestionView = true,
                    onClickSubmit = { index ->
                        //Toast.makeText(context, "Coming soon $index - ${flatReadingModel.questionSet.size}", Toast.LENGTH_LONG).show()
                        val flatReadingRequest: FlatReadingRequest = FlatReadingRequest(
                            userAuthToken = userTaskItem.userAuthToken,
                            categoryId = userTaskItem.categoryId,
                            itemId = userTaskItem.itemId,
                            questionId = flatReadingModel.questionSet[index].questionId,
                        )
                        //Toast.makeText(context, "Coming soon - ${flatReadingModel.questionSet[index].question}", Toast.LENGTH_LONG).show()
                        viewModel.onUiEvent(FlatReadingUiEvent.Submit(flatReadingRequest = flatReadingRequest))
                    }
                )*/
            }
        }
    }
}