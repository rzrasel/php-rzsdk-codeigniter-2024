package com.rzrasel.wordquiz.presentation.screens.flatpractice.layout

import android.util.Log
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.mutableStateListOf
import androidx.compose.runtime.remember
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.core.graphics.toColorInt
import androidx.lifecycle.viewmodel.compose.viewModel
import com.rzrasel.wordquiz.presentation.components.components.CustomCheckBox
import com.rzrasel.wordquiz.presentation.components.components.RoundedCornerChip
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.FlatPracticeViewModel
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeQuestionItemModel
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingQuestionDataModel

@Composable
fun FlatPracticeQuestionView(questionIndex: Int, question: FlatPracticeQuestionItemModel, viewModel: FlatPracticeViewModel = viewModel()) {
    /*var isChecked by remember { mutableStateOf(false) }
    //val isCheckedList: List<Boolean> = listOf(false, false, false, false)
    val isCheckedList: List<Boolean> = listOf()
    question.answerSet.forEach {
        isCheckedList.add(false)
    }
    //var mutableStateOfCheckedList by remember { mutableStateOf(isCheckedList) }
    //val mutableStateOfCheckedList = remember { mutableStateListOf(isCheckedList) }
    val mutableStateOfCheckedList = remember { mutableStateListOf(*isCheckedList.toTypedArray()) }*/
    //
    val mutableStateOfCheckedList = remember { mutableStateListOf<Boolean>() }
    repeat(question.answerSet.size) {
        mutableStateOfCheckedList.add(false)
    }
    //val mutableStateOfCheckedList = MutableStateFlow<FlatPracticeQuestionSetModel>(question)
    //val mcqState by viewModel.mcqQuestionState.collectAsState()
    //
    Text(
        text = question.question!!,
        fontWeight = FontWeight.Bold,
    )
    Spacer(Modifier.height(16.dp))
    RoundedCornerChip(
        modifier = Modifier
            .fillMaxWidth()
            .background(Color("#f3f3f8".toColorInt()))
            .padding(0.dp),
        contentModifier = Modifier
            .fillMaxWidth()
            .background(Color("#f3f3f8".toColorInt()))
            .padding(8.dp),
        cornerRadius = 6,
        //backgroundColor = Color.Blue,
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth(),
        ) {
            /*question.answerSet.forEachIndexed { index, item ->
                CustomCheckBox(
                    modifier = Modifier
                        .fillMaxWidth(),
                    text = item.answer,
                    textColor = Color("#232323".toColorInt()),
                    checkedColor = Color("#ff239cbc".toColorInt()),
                    borderCheckedColor = Color("#ff239cbc".toColorInt()),
                    borderUncheckedColor = Color("#ffde7b73".toColorInt()),
                    isChecked = mutableStateOfCheckedList[index],
                    onCheckedChange = {
                        mutableStateOfCheckedList[index] = it
                    },
                )
            }*/
            question.answerSet.forEachIndexed { index, option ->
                mutableStateOfCheckedList[index] = option.isSelected
                /*CustomCheckBox(
                    modifier = Modifier
                        .fillMaxWidth(),
                    text = option.answer,
                    textColor = Color("#232323".toColorInt()),
                    checkedColor = Color("#ff239cbc".toColorInt()),
                    borderCheckedColor = Color("#ff239cbc".toColorInt()),
                    borderUncheckedColor = Color("#ffde7b73".toColorInt()),
                    //isChecked = option.isSelected,
                    isChecked = mutableStateOfCheckedList[index],
                    onCheckedChange = {
                        //option.isSelected = it
                        mutableStateOfCheckedList[index] = it
                        Log.i("DEBUG_LOG", "onCheckedChange 1 ${question.toString()}")
                        viewModel.toggleOption(questionIndex, index)
                        Log.i("DEBUG_LOG", "onCheckedChange 2 ${question.toString()}")
                    },
                )*/
            }
        }
    }
}